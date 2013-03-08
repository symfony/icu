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
 * Default implementation of {@link RegionBundleInterface}.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class RegionBundle extends AbstractResourceBundle implements RegionBundleInterface
{
    /**
     * {@inheritdoc}
     */
    public function getCountryName($locale, $country)
    {
        return $this->readEntry($locale, 'Countries', $country);
    }

    /**
     * {@inheritdoc}
     */
    public function getCountryNames($locale)
    {
        return $this->readMergedEntry($locale, 'Countries');
    }
}
