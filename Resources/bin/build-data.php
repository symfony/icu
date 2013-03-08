<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Symfony\Component\Icu\Icu;
use Symfony\Component\Icu\ResourceBundle\Compilation\ResourceBundleCompilation;
use Symfony\Component\Icu\ResourceBundle\Compilation\CompilationContext;
use Symfony\Component\Icu\ResourceBundle\Compilation\Rule\CurrencyBundleCompilationRule;
use Symfony\Component\Icu\ResourceBundle\Compilation\Rule\LanguageBundleCompilationRule;
use Symfony\Component\Icu\ResourceBundle\Compilation\Rule\LocaleBundleCompilationRule;
use Symfony\Component\Icu\ResourceBundle\Compilation\Rule\RegionBundleCompilationRule;
use Symfony\Component\Icu\ResourceBundle\Compilation\ResourceBundleCompiler;
use Symfony\Component\Icu\Util\SvnRepository;
use Symfony\Component\Filesystem\Filesystem;

////////////////////////////////////////////////////////////////////////////////

define('LINE_WIDTH', 70);

define('LINE', str_repeat('-', LINE_WIDTH) . "\n");

function bailout($message)
{
    echo "$message\n";

    exit(1);
}

function normalize_icu_version($version)
{
    preg_match('/^(?P<version>[0-9]\.[0-9]|[0-9]{2,})/', $version, $matches);

    return $matches['version'];
}

function usage()
{
    bailout(<<<MESSAGE
Usage: php build-data.php [icu-version] [autoload-path]

Builds or updates the ICU data for Symfony2 to a specific ICU version. If the
ICU version is not provided, the script will build the version installed on the
current system.

In the second parameter you can optionally pass the path to the autoload file,
which is necessary if you did not run composer in the component.

Examples:

    php build-data.php
    Builds the ICU data for the version of ICU installed on the system.

    php build-data.php 4.8
    Builds the ICU data for ICU version 4.8.

    php build-data.php 4.8 /path/to/autoload.php
    Builds the ICU data for version 4.8 using the autoload.php file found in
    /path/to/autoload.php.

Read the CONTRIBUTING.md file for more information.

MESSAGE
    );
}

////////////////////////////////////////////////////////////////////////////////

if ($GLOBALS['argc'] < 1) {
    usage();
}

// Check which autoloader to use
$autoload = isset($GLOBALS['argv'][2])
    ? $GLOBALS['argv'][2]
    : __DIR__ . '/../../vendor/autoload.php';

require_once realpath($autoload);

// Print a title to the shell
$title = "ICU Resource File Update";
$padding = (int) ((LINE_WIDTH - strlen($title))/2);

echo LINE;
echo str_repeat(' ', $padding) . $title . "\n";
echo LINE;

if (!Icu::isInstalled()) {
    bailout('The intl extension for PHP is not installed. Aborting.');
}

$systemVersion = Icu::getVersion();

echo "Found intl extension with ICU version $systemVersion.\n";

// Determine ICU version to install
$version = isset($GLOBALS['argv'][1]) ? $GLOBALS['argv'][1] : $systemVersion;
$version = normalize_icu_version($version);

if ($version !== normalize_icu_version($systemVersion)) {
    echo wordwrap(
        "ATTENTION: You are trying to install version $version which differs"
        . " from version $systemVersion installed for PHP. This could cause"
        . " troubles during the bundle compilation.\n",
        LINE_WIDTH
    );
}

$urls = parse_ini_file(__DIR__ . '/icu.ini');

if (!isset($urls[$version])) {
    bailout('The version ' . $version . ' is not available in the icu.ini file.');
}

echo "icu.ini parsed. Available versions:\n";

foreach ($urls as $urlVersion => $url) {
    echo "  $urlVersion\n";
}

echo "Starting SVN checkout for version $version. This may take a while...\n";

$svn = SvnRepository::download($urls[$version], $version);

echo "SVN checkout complete.\n";

echo "Looking for genrb...\n";

$build = false;

exec('which genrb', $output, $status);

if (0 !== $status) {
    echo "genrb is not installed. ";
    $build = true;
} else {
    exec('genrb --version 2>&1', $output, $status);

    if (0 !== $status) {
        bailout('genrb failed.');
    }

    if (!preg_match('/ICU version ([\d\.]+)/', implode('', $output), $matches)) {
        echo "Could not determine version of genrb. ";
        $build = true;
    } else {
        $genrbVersion = normalize_icu_version($matches[1]);

        if ($genrbVersion !== $version) {
            echo "Version $genrbVersion of genrb does not match version $version of the requested install. ";
            $build = true;
        }
    }
}

if ($build) {
    echo "Building genrb.\n";

    echo "Running configure...\n";

    exec($svn->getPath() . '/configure 2>&1', $output, $status);

    if (0 !== $status) {
        $output = implode("\n", $output);
        echo "Error:\n" . LINE . "$output\n" . LINE;

        bailout("configure failed.");
    }

    exec('cd ' . $svn->getPath() . '/tools', $output, $status);

    if (0 !== $status) {
        bailout('Could not switch to directory /tools.');
    }

    echo "Running make...\n";

    exec('make 2>&1', $output, $status);

    if (0 !== $status) {
        $output = implode("\n", $output);
        echo "Error:\n" . LINE . "$output\n" . LINE;

        bailout("make failed.");
    }

    $genrb = $svn->getPath() . '/bin/genrb';

    echo "Using genrb version $version provided in the SVN checkout.\n";
} else {
    $genrb = 'genrb';

    echo "Using genrb version $genrbVersion installed on the system.\n";
}

echo "Preparing resource bundle compilation...\n";

$context = new CompilationContext(
    $svn->getPath() . '/data',
    realpath(__DIR__ . '/../icu'),
    realpath(__DIR__ . '/../stub'),
    new Filesystem(),
    new ResourceBundleCompiler($genrb),
    $version
);

$compilation = new ResourceBundleCompilation();
$compilation->addRule(new LanguageBundleCompilationRule());
$compilation->addRule(new RegionBundleCompilationRule());
$compilation->addRule(new CurrencyBundleCompilationRule());
$compilation->addRule(new LocaleBundleCompilationRule());

echo "Starting resource bundle compilation. This may take a while...\n";

$compilation->run($context);

echo "Resource bundle compilation complete.\n";

$svnInfo = <<<SVN_INFO
SVN information
===============

URL: {$svn->getUrl()}
Revision: {$svn->getLastCommit()->getRevision()}
Author: {$svn->getLastCommit()->getAuthor()}
Date: {$svn->getLastCommit()->getDate()}

SVN_INFO;

file_put_contents(__DIR__ . '/../svn-info.txt', $svnInfo);

echo "Done.\n";
