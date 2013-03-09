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

/**
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class IcuData
{
    /**
     * Returns the version of the bundled ICU data.
     *
     * @return string The version string.
     */
    public static function getVersion()
    {
        return trim(file_get_contents(__DIR__ . '/Resources/data/version.txt'));
    }

    /**
     * Returns whether the ICU data can be loaded.
     *
     * @return Boolean Returns true if the ICU data can be loaded, false otherwise.
     */
    public static function isLoadable()
    {
        return class_exists('\ResourceBundle');
    }

    /**
     * Returns the path to the directory where the resource bundles are stored.
     *
     * @return string The absolute path to the resource directory.
     */
    public static function getResourceDirectory()
    {
        return realpath(__DIR__ . '/Resources/data');
    }

    /**
     * This class must not be instantiated.
     */
    private function __construct() {}
}
