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
 * Reads individual entries of a resource file.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
interface ResourceEntryReaderInterface extends ResourceBundleReaderInterface
{
    /**
     * Reads an entry from a resource bundle.
     *
     * An entry can be selected from the resource bundle by passing the path
     * to that entry in the bundle. For example, if the bundle is structured
     * like this:
     *
     *     TopLevel
     *         NestedLevel
     *             Entry: Value
     *
     * Then the value can be read by calling:
     *
     *     $reader->readEntry('...', 'en', 'TopLevel', 'NestedLevel', 'Entry');
     *
     * @param string $path      The path to the resource bundle.
     * @param string $locale    The locale to read.
     * @param string $index,... The indices to read from the bundle.
     *
     * @return mixed Returns an array or {@link \ArrayAccess} instance for
     *               complex data, a scalar value for simple data and NULL
     *               if the given path could not be accessed.
     */
    public function readEntry($path, $locale);

    /**
     * Reads an entry from a resource bundle taking fallback locales into account.
     *
     * If the entry contains a collection values, this collection is merged
     * with the corresponding collection in the fallback locale
     * (e.g. "en" for "en_US").
     *
     * @param string $path   The path to the resource bundle.
     * @param string $locale The locale to read.
     * @param string $index,... The indices to read from the bundle.
     *
     * @return mixed Returns an array or {@link \ArrayAccess} instance for
     *               complex data, a scalar value for simple data and NULL
     *               if the given path could not be accessed.
     */
    public function readMergedEntry($path, $locale);
}
