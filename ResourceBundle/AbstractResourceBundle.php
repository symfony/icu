<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Icu\ResourceBundle;

use Symfony\Component\Icu\Icu;
use Symfony\Component\Icu\ResourceBundle\Reader\ResourceEntryReaderInterface;

/**
 * Base class for {@link ResourceBundleInterface} implementations.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
abstract class AbstractResourceBundle implements ResourceBundleInterface
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var ResourceEntryReaderInterface
     */
    private $entryReader;

    /**
     * Creates a bundle at the given path using the given reader for reading
     * bundle entries.
     *
     * @param string                       $path        The path to the bundle.
     * @param ResourceEntryReaderInterface $entryReader The reader for reading
     *                                                  the bundle.
     */
    public function __construct($path, ResourceEntryReaderInterface $entryReader)
    {
        $this->path = $path;
        $this->entryReader = $entryReader;
    }

    /**
     * {@inheritdoc}
     */
    public function getAvailableLocales()
    {
        return $this->entryReader->readLocales($this->path);
    }

    /**
     * Reads an entry from the bundle.
     *
     * @param string $locale    The locale to read entries in.
     * @param string $index,... The indices to read.
     *
     * @return mixed The bundle entry.
     */
    protected function readEntry($locale)
    {
        return $this->entryReader->readEntry(
            $this->path,
            $locale,
            array_slice(func_get_args(), 1)
        );
    }

    /**
     * Reads an entry from the bundle and its fallback locales.
     *
     * If the entry contains an array or \ArrayAccess object, this object is
     * merged with the content of the fallback locale.
     *
     * @param string $locale    The locale to read entries in.
     * @param string $index,... The indices to read.
     *
     * @return mixed The bundle entry.
     */
    protected function readMergedEntry($locale)
    {
        return $this->entryReader->readMergedEntry(
            $this->path,
            $locale,
            array_slice(func_get_args(), 1)
        );
    }
}
