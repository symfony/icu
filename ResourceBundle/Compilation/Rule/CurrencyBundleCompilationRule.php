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
use Symfony\Component\Icu\ResourceBundle\CurrencyBundle;

/**
 * The rule for compiling the currency bundle.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class CurrencyBundleCompilationRule implements CompilationRuleInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundleName()
    {
        return 'curr';
    }

    /**
     * {@inheritdoc}
     */
    public function beforeCompile(CompilationContextInterface $context)
    {
        // The currency data is contained in the locales and misc bundles
        // in ICU <= 4.2
        if (version_compare($context->getIcuVersion(), '4.2', '<=')) {
            return array(
                $context->getSourceDir() . '/misc/supplementalData.txt',
                $context->getSourceDir() . '/locales'
            );
        }

        return $context->getSourceDir() . '/curr';
    }

    /**
     * {@inheritdoc}
     */
    public function afterCompile(CompilationContextInterface $context)
    {
        // \ResourceBundle does not like locale names with uppercase chars, so rename
        // the resource file
        // See: http://bugs.php.net/bug.php?id=54025
        $fileName = $context->getBinaryDir() . '/curr/supplementalData.res';
        $fileNameLower = $context->getBinaryDir() . '/curr/supplementaldata.res';

        $context->getFilesystem()->rename($fileName, $fileNameLower);
    }

    /**
     * {@inheritdoc}
     */
    public function beforeCreateStub(CompilationContextInterface $context)
    {
        $currencies = array();
        $currencyBundle = Icu::getCurrencyBundle();

        foreach ($currencyBundle->getCurrencyNames('en') as $code => $name) {
            $currencies[$code] = array(
                CurrencyBundle::INDEX_NAME => $name,
                CurrencyBundle::INDEX_SYMBOL => $currencyBundle->getCurrencySymbol('en', $code),
                CurrencyBundle::INDEX_FRACTION_DIGITS => $currencyBundle->getFractionDigits($code),
                CurrencyBundle::INDEX_ROUNDING_INCREMENT => $currencyBundle->getRoundingIncrement($code),
            );
        }

        return array(
            'Currencies' => $currencies,
        );
    }

    /**
     * {@inheritdoc}
     */
    public function afterCreateStub(CompilationContextInterface $context)
    {
    }
}
