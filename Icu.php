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

use Symfony\Component\Icu\ResourceBundle\Icu\IcuCurrencyBundle;
use Symfony\Component\Icu\ResourceBundle\Icu\IcuLanguageBundle;
use Symfony\Component\Icu\ResourceBundle\Icu\IcuLocaleBundle;
use Symfony\Component\Icu\ResourceBundle\Icu\IcuRegionBundle;
use Symfony\Component\Icu\ResourceBundle\Reader\BinaryBundleReader;
use Symfony\Component\Icu\ResourceBundle\Reader\PhpBundleReader;
use Symfony\Component\Icu\ResourceBundle\Reader\ResourceEntryReader;
use Symfony\Component\Icu\ResourceBundle\CurrencyBundle;
use Symfony\Component\Icu\ResourceBundle\LanguageBundle;
use Symfony\Component\Icu\ResourceBundle\LocaleBundle;
use Symfony\Component\Icu\ResourceBundle\RegionBundle;

/**
 * Gives access to the data of the ICU library.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class Icu
{
    /**
     * @var ResourceBundle\CurrencyBundleInterface
     */
    private static $currencyBundle;

    /**
     * @var ResourceBundle\LanguageBundleInterface
     */
    private static $languageBundle;

    /**
     * @var ResourceBundle\LocaleBundleInterface
     */
    private static $localeBundle;

    /**
     * @var ResourceBundle\RegionBundleInterface
     */
    private static $regionBundle;

    /**
     * @var ResourceBundle\Reader\ResourceEntryReaderInterface
     */
    private static $binaryReader;

    /**
     * @var ResourceBundle\Reader\ResourceEntryReaderInterface
     */
    private static $phpReader;

    /**
     * Returns whether the ICU library is installed.
     *
     * @return Boolean Returns true if the ICU library is installed, false otherwise.
     */
    public static function isInstalled()
    {
        return class_exists('\ResourceBundle');
    }

    /**
     * Returns the bundle containing currency information.
     *
     * @return ResourceBundle\CurrencyBundleInterface The currency resource bundle.
     */
    public static function getCurrencyBundle()
    {
        if (null === self::$currencyBundle) {
            self::$currencyBundle = self::isInstalled()
                ? new IcuCurrencyBundle(__DIR__ . '/Resources/icu/curr', self::getBinaryReader())
                : new CurrencyBundle(__DIR__ . '/Resources/stub/curr', self::getPhpReader());
        }

        return self::$currencyBundle;
    }

    /**
     * Returns the bundle containing language information.
     *
     * @return ResourceBundle\LanguageBundleInterface The language resource bundle.
     */
    public static function getLanguageBundle()
    {
        if (null === self::$languageBundle) {
            self::$languageBundle = self::isInstalled()
                ? new IcuLanguageBundle(__DIR__ . '/Resources/icu/lang', self::getBinaryReader())
                : new LanguageBundle(__DIR__ . '/Resources/stub/lang', self::getPhpReader());
        }

        return self::$languageBundle;
    }

    /**
     * Returns the bundle containing locale information.
     *
     * @return ResourceBundle\LocaleBundleInterface The locale resource bundle.
     */
    public static function getLocaleBundle()
    {
        if (null === self::$localeBundle) {
            self::$localeBundle = self::isInstalled()
                ? new IcuLocaleBundle(__DIR__ . '/Resources/icu/locales', self::getBinaryReader())
                : new LocaleBundle(__DIR__ . '/Resources/stub/locales', self::getPhpReader());
        }

        return self::$localeBundle;
    }

    /**
     * Returns the bundle containing region information.
     *
     * @return ResourceBundle\RegionBundleInterface The region resource bundle.
     */
    public static function getRegionBundle()
    {
        if (null === self::$regionBundle) {
            self::$regionBundle = self::isInstalled()
                ? new IcuRegionBundle(__DIR__ . '/Resources/icu/region', self::getBinaryReader())
                : new RegionBundle(__DIR__ . '/Resources/stub/region', self::getPhpReader());
        }

        return self::$regionBundle;
    }

    /**
     * Returns the version of the installed ICU library.
     *
     * @return null|string The ICU version or NULL if it could not be determined.
     */
    public static function getVersion()
    {
        if (defined('INTL_ICU_VERSION')) {
            return INTL_ICU_VERSION;
        }

        try {
            $reflector = new \ReflectionExtension('intl');
        } catch (\ReflectionException $e) {
            return null;
        }

        ob_start();
        $reflector->info();
        $output = strip_tags(ob_get_clean());
        preg_match('/^ICU version (?:=>)?(.*)$/m', $output, $matches);

        return trim($matches[1]);
    }

    /**
     * Returns the version of the installed ICU data.
     *
     * @return null|string The ICU data version or NULL if it could not be determined.
     */
    public static function getDataVersion()
    {
        if (defined('INTL_ICU_DATA_VERSION')) {
            return INTL_ICU_DATA_VERSION;
        }

        try {
            $reflector = new \ReflectionExtension('intl');
        } catch (\ReflectionException $e) {
            return null;
        }

        ob_start();
        $reflector->info();
        $output = strip_tags(ob_get_clean());
        preg_match('/^ICU Data version (?:=>)?(.*)$/m', $output, $matches);

        return trim($matches[1]);
    }

    /**
     * Returns a resource entry reader for binary .res resource bundle files.
     *
     * @return ResourceBundle\Reader\ResourceEntryReaderInterface The resource reader.
     */
    private static function getBinaryReader()
    {
        if (null === self::$binaryReader) {
            self::$binaryReader = new ResourceEntryReader(new BinaryBundleReader());
        }

        return self::$binaryReader;
    }

    /**
     * Returns a resource entry reader for .php resource bundle files.
     *
     * @return ResourceBundle\Reader\ResourceEntryReaderInterface The resource reader.
     */
    private static function getPhpReader()
    {
        if (null === self::$phpReader) {
            self::$phpReader = new ResourceEntryReader(new PhpBundleReader());
        }

        return self::$phpReader;
    }

    /**
     * This class must not be instantiated.
     */
    private function __construct() {}
}
