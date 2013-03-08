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
 * The rule for compiling the language bundle.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class LanguageBundleCompilationRule implements CompilationRuleInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundleName()
    {
        return 'lang';
    }

    /**
     * {@inheritdoc}
     */
    public function beforeCompile(CompilationContextInterface $context)
    {
        // The language data is contained in the locales bundle in ICU <= 4.2
        if (version_compare($context->getIcuVersion(), '4.2', '<=')) {
            return $context->getSourceDir() . '/locales';
        }

        return $context->getSourceDir() . '/lang';
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
            'Languages' => Icu::getLanguageBundle()->getLanguageNames('en'),
            'Scripts' => Icu::getLanguageBundle()->getScriptNames('en'),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function afterCreateStub(CompilationContextInterface $context)
    {
    }
}
