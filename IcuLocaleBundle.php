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

use Symfony\Component\Intl\ResourceBundle\LocaleBundle;
use Symfony\Component\Intl\ResourceBundle\Reader\ResourceEntryReaderInterface;

/**
 * An ICU-specific implementation of {@link \Symfony\Component\Intl\ResourceBundle\LocaleBundleInterface}.
 *
 * This class normalizes the data of the ICU .res files to satisfy the contract
 * defined in {@link \Symfony\Component\Intl\ResourceBundle\LocaleBundleInterface}.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class IcuLocaleBundle extends LocaleBundle
{
    public function __construct(ResourceEntryReaderInterface $entryReader)
    {
        parent::__construct(realpath(IcuData::getResourceDirectory() . '/locales'), $entryReader);
    }

    /**
     * {@inheritdoc}
     */
    public function getLocaleNames($locale)
    {
        $locales = parent::getLocaleNames($locale);

        $collator = new \Collator($locale);
        $collator->asort($locales);

        return $locales;
    }
}
