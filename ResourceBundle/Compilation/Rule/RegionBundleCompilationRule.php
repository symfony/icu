<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Icu\ResourceBundle\Compilation\Rule;

use Symfony\Component\Icu\ResourceBundle\Compilation\CompilationContextInterface;
use Symfony\Component\Icu\Icu;

/**
 * The rule for compiling the region bundle.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class RegionBundleCompilationRule implements CompilationRuleInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundleName()
    {
        return 'region';
    }

    /**
     * {@inheritdoc}
     */
    public function beforeCompile(CompilationContextInterface $context)
    {
        // The region data is contained in the locales bundle in ICU <= 4.2
        if (version_compare($context->getIcuVersion(), '4.2', '<=')) {
            return $context->getSourceDir() . '/locales';
        }

        return $context->getSourceDir() . '/region';
    }

    /**
     * {@inheritdoc}
     */
    public function afterCompile(CompilationContextInterface $context)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function beforeCreateStub(CompilationContextInterface $context)
    {
        return array(
            'Countries' => Icu::getRegionBundle()->getCountryNames('en'),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function afterCreateStub(CompilationContextInterface $context)
    {
    }
}
