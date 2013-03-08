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
 * Default implementation of {@link LocaleBundleInterface}.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class LocaleBundle extends AbstractResourceBundle implements LocaleBundleInterface
{
    /**
     * {@inheritdoc}
     */
    public function getLocaleName($locale, $ofLocale)
    {
        return $this->readEntry($locale, 'Locales', $ofLocale);
    }

    /**
     * {@inheritdoc}
     */
    public function getLocaleNames($locale)
    {
        return $this->readMergedEntry($locale, 'Locales');
    }
}
