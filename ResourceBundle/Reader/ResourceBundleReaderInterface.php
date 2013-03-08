<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Icu\ResourceBundle\Reader;

/**
 * Reads resource bundle files.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
interface ResourceBundleReaderInterface
{
    /**
     * Reads a resource bundle.
     *
     * @param string $path   The path to the resource bundle.
     * @param string $locale The locale to read.
     *
     * @return mixed Returns an array or {@link \ArrayAccess} instance for
     *               complex data, a scalar value otherwise.
     */
    public function read($path, $locale);

    /**
     * Reads the available locales of a resource bundle.
     *
     * @param string $path The path to the resource bundle.
     *
     * @return string[] A list of supported locale codes.
     */
    public function readLocales($path);
}
