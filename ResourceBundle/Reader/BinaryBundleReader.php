<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Icu\ResourceBundle\Reader;

use Symfony\Component\Icu\Exception\RuntimeException;
use Symfony\Component\Icu\Util\ArrayAccessibleResourceBundle;

/**
 * Reads binary .res resource bundles.
 *
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class BinaryBundleReader extends AbstractBundleReader implements ResourceBundleReaderInterface
{
    /**
     * {@inheritdoc}
     */
    public function read($path, $locale)
    {
        $bundle = new \ResourceBundle($locale, $path);

        if (null === $bundle) {
            throw new RuntimeException(sprintf(
                'Could not load the resource bundle "%s/%s.res".',
                $path,
                $locale
            ));
        }

        return new ArrayAccessibleResourceBundle($bundle);
    }

    /**
     * {@inheritdoc}
     */
    protected function getFileExtension()
    {
        return 'res';
    }
}
