<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Icu\Tests\ResourceBundle\Reader;

use Symfony\Component\Icu\ResourceBundle\Reader\ResourceEntryReader;

/**
 * @author Bernhard Schussek <bschussek@gmail.com>
 */
class ResourceEntryReaderTest extends \PHPUnit_Framework_TestCase
{
    const RES_DIR = '/res/dir';

    /**
     * @var ResourceEntryReader
     */
    private $reader;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    private $readerImpl;

    protected function setUp()
    {
        $this->readerImpl = $this->getMock('Symfony\Component\Icu\ResourceBundle\Reader\ResourceBundleReaderInterface');
        $this->reader = new ResourceEntryReader($this->readerImpl);
    }

    public function testReadLocales()
    {
        $locales = array('en', 'de', 'fr');

        $this->readerImpl->expects($this->once())
            ->method('readLocales')
            ->with(self::RES_DIR)
            ->will($this->returnValue($locales));

        $this->assertSame($locales, $this->reader->readLocales(self::RES_DIR));
    }

    public function testRead()
    {
        $data = array('foo', 'bar');

        $this->readerImpl->expects($this->once())
            ->method('read')
            ->with(self::RES_DIR, 'en')
            ->will($this->returnValue($data));

        $this->assertSame($data, $this->reader->read(self::RES_DIR, 'en'));
    }

    public function testReadEntryNoParams()
    {
        $data = array('foo', 'bar');

        $this->readerImpl->expects($this->once())
            ->method('read')
            ->with(self::RES_DIR, 'en')
            ->will($this->returnValue($data));

        $this->assertSame($data, $this->reader->readEntry(self::RES_DIR, 'en'));
    }

    public function testReadEntryWithParams()
    {
        $data = array('Foo' => array('Bar' => 'Baz'));

        $this->readerImpl->expects($this->once())
            ->method('read')
            ->with(self::RES_DIR, 'en')
            ->will($this->returnValue($data));

        $this->assertSame('Baz', $this->reader->readEntry(self::RES_DIR, 'en', 'Foo', 'Bar'));
    }

    public function testReadEntryWithArrayParam()
    {
        $data = array('Foo' => array('Bar' => 'Baz'));

        $this->readerImpl->expects($this->once())
            ->method('read')
            ->with(self::RES_DIR, 'en')
            ->will($this->returnValue($data));

        $this->assertSame('Baz', $this->reader->readEntry(self::RES_DIR, 'en', array('Foo', 'Bar')));
    }

    public function testReadEntryWithUnresolvablePath()
    {
        $data = array('Foo' => 'Baz');

        $this->readerImpl->expects($this->once())
            ->method('read')
            ->with(self::RES_DIR, 'en')
            ->will($this->returnValue($data));

        $this->assertNull($this->reader->readEntry(self::RES_DIR, 'en', 'Foo', 'Bar'));
    }

    public function testReadMergedEntryNoParams()
    {
        $parentData = array('foo', 'bar');
        $childData = array('baz');

        $this->readerImpl->expects($this->at(0))
            ->method('read')
            ->with(self::RES_DIR, 'en_GB')
            ->will($this->returnValue($childData));

        $this->readerImpl->expects($this->at(1))
            ->method('read')
            ->with(self::RES_DIR, 'en')
            ->will($this->returnValue($parentData));

        $data = array('foo', 'bar', 'baz');

        $this->assertSame($data, $this->reader->readMergedEntry(self::RES_DIR, 'en_GB'));
    }

    public function testReadMergedEntryWithParams()
    {
        $parentData = array('Foo' => array('Bar' => array('one', 'two')));
        $childData = array('Foo' => array('Bar' => array('three')));

        $this->readerImpl->expects($this->at(0))
            ->method('read')
            ->with(self::RES_DIR, 'en_GB')
            ->will($this->returnValue($childData));

        $this->readerImpl->expects($this->at(1))
            ->method('read')
            ->with(self::RES_DIR, 'en')
            ->will($this->returnValue($parentData));

        $data = array('one', 'two', 'three');

        $this->assertSame($data, $this->reader->readMergedEntry(self::RES_DIR, 'en_GB', 'Foo', 'Bar'));
    }

    public function testReadMergedEntryWithArrayParam()
    {
        $parentData = array('Foo' => array('Bar' => array('one', 'two')));
        $childData = array('Foo' => array('Bar' => array('three')));

        $this->readerImpl->expects($this->at(0))
            ->method('read')
            ->with(self::RES_DIR, 'en_GB')
            ->will($this->returnValue($childData));

        $this->readerImpl->expects($this->at(1))
            ->method('read')
            ->with(self::RES_DIR, 'en')
            ->will($this->returnValue($parentData));

        $data = array('one', 'two', 'three');

        $this->assertSame($data, $this->reader->readMergedEntry(self::RES_DIR, 'en_GB', array('Foo', 'Bar')));
    }

    public function testReadMergedEntryWithUnresolvablePath()
    {
        $parentData = array('Foo' => 'Bar');
        $childData = array('Foo' => 'Baz');

        $this->readerImpl->expects($this->at(0))
            ->method('read')
            ->with(self::RES_DIR, 'en_GB')
            ->will($this->returnValue($childData));

        $this->readerImpl->expects($this->at(1))
            ->method('read')
            ->with(self::RES_DIR, 'en')
            ->will($this->returnValue($parentData));

        $this->assertNull($this->reader->readMergedEntry(self::RES_DIR, 'en_GB', array('Foo', 'Bar')));
    }

    public function testReadMergedEntryWithUnresolvablePathInParent()
    {
        $parentData = array('Foo' => 'Bar');
        $childData = array('Foo' => array('Bar' => array('three')));

        $this->readerImpl->expects($this->at(0))
            ->method('read')
            ->with(self::RES_DIR, 'en_GB')
            ->will($this->returnValue($childData));

        $this->readerImpl->expects($this->at(1))
            ->method('read')
            ->with(self::RES_DIR, 'en')
            ->will($this->returnValue($parentData));

        $data = array('three');

        $this->assertSame($data, $this->reader->readMergedEntry(self::RES_DIR, 'en_GB', array('Foo', 'Bar')));
    }

    public function testReadMergedEntryWithUnresolvablePathInChild()
    {
        $parentData = array('Foo' => array('Bar' => array('one', 'two')));
        $childData = array('Foo' => 'Baz');

        $this->readerImpl->expects($this->at(0))
            ->method('read')
            ->with(self::RES_DIR, 'en_GB')
            ->will($this->returnValue($childData));

        $this->readerImpl->expects($this->at(1))
            ->method('read')
            ->with(self::RES_DIR, 'en')
            ->will($this->returnValue($parentData));

        $data = array('one', 'two');

        $this->assertSame($data, $this->reader->readMergedEntry(self::RES_DIR, 'en_GB', array('Foo', 'Bar')));
    }

    public function testReadMergedEntryWithTraversables()
    {
        $parentData = array('Foo' => array('Bar' => new \ArrayObject(array('one', 'two'))));
        $childData = array('Foo' => array('Bar' => new \ArrayObject(array('three'))));

        $this->readerImpl->expects($this->at(0))
            ->method('read')
            ->with(self::RES_DIR, 'en_GB')
            ->will($this->returnValue($childData));

        $this->readerImpl->expects($this->at(1))
            ->method('read')
            ->with(self::RES_DIR, 'en')
            ->will($this->returnValue($parentData));

        $data = array('one', 'two', 'three');

        $this->assertSame($data, $this->reader->readMergedEntry(self::RES_DIR, 'en_GB', 'Foo', 'Bar'));
    }
}
