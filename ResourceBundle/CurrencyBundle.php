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

/**
 * Default implementation of {@link CurrencyBundleInterface}.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class CurrencyBundle extends AbstractResourceBundle implements CurrencyBundleInterface
{
    const INDEX_NAME = 0;

    const INDEX_SYMBOL = 1;

    const INDEX_FRACTION_DIGITS = 2;

    const INDEX_ROUNDING_INCREMENT = 3;

    /**
     * {@inheritdoc}
     */
    public function getCurrencySymbol($locale, $currency)
    {
        return $this->readEntry($locale, 'Currencies', $currency, static::INDEX_SYMBOL);
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrencyName($locale, $currency)
    {
        return $this->readEntry($locale, 'Currencies', $currency, static::INDEX_NAME);
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrencyNames($locale)
    {
        $currencies = $this->readMergedEntry($locale, 'Currencies');

        array_walk($currencies, function (&$value) {
            $value = $value[static::INDEX_NAME];
        });

        return $currencies;
    }

    /**
     * {@inheritdoc}
     */
    public function getFractionDigits($currency)
    {
        return $this->readEntry('en', 'Currencies', $currency, static::INDEX_FRACTION_DIGITS);
    }

    /**
     * {@inheritdoc}
     */
    public function getRoundingIncrement($currency)
    {
        return $this->readEntry('en', 'Currencies', $currency, static::INDEX_ROUNDING_INCREMENT);
    }
}
