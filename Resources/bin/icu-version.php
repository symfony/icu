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

// Check which autoloader to use
$autoload = isset($GLOBALS['argv'][1])
    ? $GLOBALS['argv'][1]
    : __DIR__ . '/../../vendor/autoload.php';

require_once realpath($autoload);

echo "ICU version: ";
echo Icu::getVersion() . "\n";
