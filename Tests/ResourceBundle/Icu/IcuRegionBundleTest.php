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

use Symfony\Component\Icu\ResourceBundle\Icu\IcuRegionBundle;

/**
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class IcuRegionBundleTest extends \PHPUnit_Framework_TestCase
{
    const RES_DIR = '/base/region';

    /**
     * @var IcuRegionBundle
     */
    private $bundle;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $reader;

    protected function setUp()
    {
        $this->reader = $this->getMock('Symfony\Component\Icu\ResourceBundle\Reader\ResourceEntryReaderInterface');
        $this->bundle = new IcuRegionBundle(self::RES_DIR, $this->reader);
    }

    public function testGetCountryNameOfUnknownCountry()
    {
        $this->reader->expects($this->never())
            ->method('readEntry');

        $this->assertNull($this->bundle->getCountryName('en', 'ZZ'));
    }

    public function testGetCountryNameOfNumericalRegion()
    {
        $this->reader->expects($this->never())
            ->method('readEntry');

        $this->assertNull($this->bundle->getCountryName('en', 123));
    }

    public function testGetCountryNameOfNumericalRegionWithLeadingZero()
    {
        $this->reader->expects($this->never())
            ->method('readEntry');

        $this->assertNull($this->bundle->getCountryName('en', '010'));
    }

    public function testGetCountryNames()
    {
        $countries = array(
            'DE' => 'Germany',
            'AT' => 'Austria',
            'ZZ' => 'Unknown Country',
            '010' => 'Europe',
            110 => 'America',
        );

        $this->reader->expects($this->once())
            ->method('readMergedEntry')
            ->with(self::RES_DIR, 'en', array('Countries'))
            ->will($this->returnValue($countries));

        $sortedCountries = array(
            'AT' => 'Austria',
            'DE' => 'Germany',
        );

        $this->assertSame($sortedCountries, $this->bundle->getCountryNames('en'));
    }
}
