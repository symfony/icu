<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Icu;

use Symfony\Component\Intl\ResourceBundle\Reader\BinaryBundleReader;
use Symfony\Component\Intl\ResourceBundle\Reader\BundleReaderInterface;

/**
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class IcuData
{
    private static $icuDataVersion;
    private static $icuDataPath;

    /**
     * Returns the version of the bundled ICU data.
     *
     * @return string The version string.
     */
    public static function getVersion()
    {
        if (self::$icuDataVersion) {
            return self::$icuDataVersion;
        }

        return self::$icuDataVersion = trim(file_get_contents(self::getResourceDirectory().'/version.txt'));
    }

    /**
     * Returns whether the ICU data is stubbed.
     *
     * @return Boolean Returns true if the ICU data is stubbed, false if it is
     *         loaded from ICU .res files.
     */
    public static function isStubbed()
    {
        return false;
    }

    /**
     * Returns the path to the directory where the resource bundles are stored.
     *
     * This library ships with 2 versions of the ICU data.
     * One compatible with the < 4.4 format and the other
     * compatible with the >= 4.4 format.
     *
     * @return string The absolute path to the resource directory.
     */
    public static function getResourceDirectory()
    {
        if (self::$icuDataPath) {
            return self::$icuDataPath;
        }

        return self::$icuDataPath = realpath(__DIR__.'/Resources/data/'.(self::getIntlVersion() >= 4.4 ? 'post-4.4' : 'pre-4.4'));
    }

    /**
     * Returns a reader for reading resource bundles in this component.
     *
     * @return BundleReaderInterface
     */
    public static function getBundleReader()
    {
        return new BinaryBundleReader();
    }

    /**
     * Returns the ICU data version from which the intl extension was compiled against.
     *
     * This code was extracted from composer/composer (under the MIT license.)
     *
     * @return The intl version
     */
    private static function getIntlVersion()
    {
        // the constant is available as of PHP 5.3.7
        if (defined('INTL_ICU_VERSION')) {
            return INTL_ICU_VERSION;
        }

        $reflector = new \ReflectionExtension('intl');

        ob_start();
        $reflector->info();
        $output = ob_get_clean();

        preg_match('/^ICU version => (.*)$/m', $output, $matches);

        return $matches[1];
    }

    /**
     * This class must not be instantiated.
     */
    private function __construct()
    {
    }
}
