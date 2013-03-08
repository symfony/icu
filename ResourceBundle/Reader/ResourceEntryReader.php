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
 * An entry reader wrapping an existing resource bundle reader.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class ResourceEntryReader implements ResourceEntryReaderInterface
{
    /**
     * @var ResourceBundleReaderInterface
     */
    private $readerImpl;

    /**
     * Creates an entry reader based on the given resource bundle reader.
     *
     * @param ResourceBundleReaderInterface $readerImpl A resource bundle reader to use.
     */
    public function __construct(ResourceBundleReaderInterface $readerImpl)
    {
        $this->readerImpl = $readerImpl;
    }

    /**
     * {@inheritdoc}
     */
    public function read($path, $locale)
    {
        return $this->readerImpl->read($path, $locale);
    }

    /**
     * {@inheritdoc}
     */
    public function readLocales($path)
    {
        return $this->readerImpl->readLocales($path);
    }

    /**
     * {@inheritdoc}
     */
    public function readEntry($path, $locale)
    {
        $indices = array_slice(func_get_args(), 2);

        if (1 === count($indices) && is_array($indices[0])) {
            $indices = $indices[0];
        }

        return $this->readEntryImpl($path, $locale, $indices);
    }

    /**
     * {@inheritdoc}
     */
    public function readMergedEntry($path, $locale)
    {
        $indices = array_slice(func_get_args(), 2);

        if (1 === count($indices) && is_array($indices[0])) {
            $indices = $indices[0];
        }

        return $this->readMergedEntryImpl($path, $locale, $indices);
    }

    private function readEntryImpl($path, $locale, array $indices)
    {
        $entry = $this->read($path, $locale);

        foreach ($indices as $index) {
            if (!$entry instanceof \ArrayAccess && !is_array($entry)) {
                return null;
            }

            $entry = $entry[$index];
        }

        return $entry;
    }

    private function readMergedEntryImpl($path, $locale, array $indices)
    {
        $entries = $this->readEntryImpl($path, $locale, $indices);

        if ($entries instanceof \Traversable) {
            $entries = iterator_to_array($entries);
        }

        if (!is_array($entries) && null !== $entries) {
            return $entries;
        }

        if (null !== ($fallbackLocale = $this->getFallbackLocale($locale))) {
            $parentEntries = $this->readMergedEntryImpl($path, $fallbackLocale, $indices);

            if ($entries || $parentEntries) {
                $entries = array_merge(
                    $parentEntries ?: array(),
                    $entries ?: array()
                );
            }
        }

        return $entries;
    }

    /**
     * Returns the fallback locale for a given locale, if any
     *
     * @param string $locale The locale to find the fallback for.
     *
     * @return string|null The fallback locale, or null if no parent exists
     */
    private function getFallbackLocale($locale)
    {
        if (false === $pos = strrpos($locale, '_')) {
            return null;
        }

        return substr($locale, 0, $pos);
    }
}
