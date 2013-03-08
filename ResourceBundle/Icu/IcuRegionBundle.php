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

use Symfony\Component\Icu\Icu;
use Symfony\Component\Icu\ResourceBundle\AbstractResourceBundle;
use Symfony\Component\Icu\ResourceBundle\RegionBundle;
use Symfony\Component\Icu\ResourceBundle\RegionBundleInterface;

/**
 * An ICU-specific implementation of {@link \Symfony\Component\Icu\ResourceBundle\RegionBundleInterface}.
 *
 * This class normalizes the data of the ICU .res files to satisfy the contract
 * defined in {@link \Symfony\Component\Icu\ResourceBundle\RegionBundleInterface}.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class IcuRegionBundle extends RegionBundle
{
    /**
     * {@inheritdoc}
     */
    public function getCountryName($locale, $country)
    {
        if ('ZZ' === $country || ctype_digit((string) $country)) {
            return null;
        }

        return parent::getCountryName($locale, $country);
    }

    /**
     * {@inheritdoc}
     */
    public function getCountryNames($locale)
    {
        $countries = parent::getCountryNames($locale);

        // "ZZ" is the code for unknown country
        unset($countries['ZZ']);

        // Global countries (f.i. "America") have numeric codes
        // Countries have alphabetic codes
        foreach ($countries as $code => $name) {
            // is_int() does not work, since some numbers start with '0' and
            // thus are stored as strings.
            // The (string) cast is necessary since ctype_digit() returns false
            // for integers.
            if (ctype_digit((string) $code)) {
                unset($countries[$code]);
            }
        }

        $collator = new \Collator($locale);
        $collator->asort($countries);

        return $countries;
    }
}
