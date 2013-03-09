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

use Symfony\Component\Intl\ResourceBundle\LanguageBundle;
use Symfony\Component\Intl\ResourceBundle\Reader\ResourceEntryReaderInterface;

/**
 * An ICU-specific implementation of {@link \Symfony\Component\Intl\ResourceBundle\LanguageBundleInterface}.
 *
 * This class normalizes the data of the ICU .res files to satisfy the contract
 * defined in {@link \Symfony\Component\Intl\ResourceBundle\LanguageBundleInterface}.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class IcuLanguageBundle extends LanguageBundle
{
    public function __construct(ResourceEntryReaderInterface $entryReader)
    {
        parent::__construct(realpath(IcuData::getResourceDirectory() . '/lang'), $entryReader);
    }

    /**
     * {@inheritdoc}
     */
    public function getLanguageName($locale, $lang, $region = null)
    {
        if ('mul' === $lang) {
            return null;
        }

        $languages = $this->readEntry($locale, 'Languages');

        // Some languages are translated together with their region,
        // i.e. "en_GB" is translated as "British English"
        if (null !== $region && isset($languages[$lang.'_'.$region])) {
            return $languages[$lang.'_'.$region];
        }

        return $languages[$lang];
    }

    /**
     * {@inheritdoc}
     */
    public function getLanguageNames($locale)
    {
        $languages = $this->readMergedEntry($locale, 'Languages');

        $collator = new \Collator($locale);
        $collator->asort($languages);

        // "mul" is the code for multiple languages
        unset($languages['mul']);

        return $languages;
    }

    /**
     * {@inheritdoc}
     */
    public function getScriptNames($locale)
    {
        $scripts = parent::getScriptNames($locale);

        $collator = new \Collator($locale);
        $collator->asort($scripts);

        return $scripts;
    }
}
