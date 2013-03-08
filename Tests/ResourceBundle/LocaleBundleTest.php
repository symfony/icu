<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Icu\Tests\ResourceBundle;

use Symfony\Component\Icu\ResourceBundle\LocaleBundle;

/**
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class LocaleBundleTest extends \PHPUnit_Framework_TestCase
{
    const RES_DIR = '/base/locales';

    /**
     * @var LocaleBundle
     */
    private $bundle;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $reader;

    protected function setUp()
    {
        $this->reader = $this->getMock('Symfony\Component\Icu\ResourceBundle\Reader\ResourceEntryReaderInterface');
        $this->bundle = new LocaleBundle(self::RES_DIR, $this->reader);
    }

    public function testGetLocaleName()
    {
        $this->reader->expects($this->once())
            ->method('readEntry')
            ->with(self::RES_DIR, 'en', array('Locales', 'de_AT'))
            ->will($this->returnValue('German (Austria)'));


        $this->assertSame('German (Austria)', $this->bundle->getLocaleName('en', 'de_AT'));
    }

    public function testGetLocaleNames()
    {
        $sortedLocales = array(
            'en_IE' => 'English (Ireland)',
            'en_GB' => 'English (United Kingdom)',
            'en_US' => 'English (United States)',
        );

        $this->reader->expects($this->once())
            ->method('readMergedEntry')
            ->with(self::RES_DIR, 'en', array('Locales'))
            ->will($this->returnValue($sortedLocales));


        $this->assertSame($sortedLocales, $this->bundle->getLocaleNames('en'));
    }
}
