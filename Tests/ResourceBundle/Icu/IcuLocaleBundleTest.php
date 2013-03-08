<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Icu\Tests\ResourceBundle\Icu;

use Symfony\Component\Icu\ResourceBundle\Icu\IcuLocaleBundle;

/**
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class IcuLocaleBundleTest extends \PHPUnit_Framework_TestCase
{
    const RES_DIR = '/base/locales';

    /**
     * @var IcuLocaleBundle
     */
    private $bundle;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $reader;

    protected function setUp()
    {
        $this->reader = $this->getMock('Symfony\Component\Icu\ResourceBundle\Reader\ResourceEntryReaderInterface');
        $this->bundle = new IcuLocaleBundle(self::RES_DIR, $this->reader);
    }

    public function testGetLocaleNames()
    {
        $locales = array(
            'en_GB' => 'English (United Kingdom)',
            'en_IE' => 'English (Ireland)',
            'en_US' => 'English (United States)',
        );

        $this->reader->expects($this->once())
            ->method('readMergedEntry')
            ->with(self::RES_DIR, 'en', array('Locales'))
            ->will($this->returnValue($locales));

        $sortedLocales = array(
            'en_IE' => 'English (Ireland)',
            'en_GB' => 'English (United Kingdom)',
            'en_US' => 'English (United States)',
        );

        $this->assertSame($sortedLocales, $this->bundle->getLocaleNames('en'));
    }
}
