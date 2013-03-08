<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Icu\ResourceBundle\Icu;

use Symfony\Component\Icu\ResourceBundle\LocaleBundle;

/**
 * An ICU-specific implementation of {@link \Symfony\Component\Icu\ResourceBundle\LocaleBundleInterface}.
 *
 * This class normalizes the data of the ICU .res files to satisfy the contract
 * defined in {@link \Symfony\Component\Icu\ResourceBundle\LocaleBundleInterface}.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class IcuLocaleBundle extends LocaleBundle
{
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
