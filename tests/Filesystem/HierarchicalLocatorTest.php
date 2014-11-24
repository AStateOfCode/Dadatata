<?php

namespace Asoc\Dadatata\Tests\Filesystem;

use Asoc\Dadatata\Filesystem\HierarchicalLocator;

class HierarchicalLocatorTest extends \PHPUnit_Framework_TestCase
{
    public function testGetRelativeDirectory()
    {
        $locator = $this->createLocator();
        $thing   = $this->createThing();
        $this->assertEquals('12/34/56', $locator->getRelativeDirectory($thing));
    }

    /**
     * @expectedException \LogicException
     */
    public function testGetRelativeDirectoryWithTooShortKey()
    {
        $thing = $this->getMock('Asoc\Dadatata\Model\ThingInterface');
        $thing->expects($this->any())->method('getKey')->will($this->returnValue('1234'));

        $locator = $this->createLocator();
        $locator->getRelativeDirectory($thing);
    }

    public function testGetDirectory()
    {
        $locator = $this->createLocator();
        $thing   = $this->createThing();
        $this->assertEquals('/tmp/12/34/56', $locator->getDirectory($thing));
    }

    public function testGetRelativeFilePath()
    {
        $locator = $this->createLocator();
        $thing   = $this->createThing();
        $this->assertEquals('12/34/56/123456789_1', $locator->getRelativeFilePath($thing));
    }

    public function testGetRelativeFilePathForFragmentTwo()
    {
        $locator = $this->createLocator();
        $thing   = $this->createThing();
        $this->assertEquals('12/34/56/123456789_2', $locator->getRelativeFilePath($thing, 2));
    }

    public function testGetFilePath()
    {
        $locator = $this->createLocator();
        $thing   = $this->createThing();
        $this->assertEquals('/tmp/12/34/56/123456789_1', $locator->getFilePath($thing));
    }

    public function testGetFilePathForFragmentTwo()
    {
        $locator = $this->createLocator();
        $thing   = $this->createThing();
        $this->assertEquals('/tmp/12/34/56/123456789_2', $locator->getFilePath($thing, 2));
    }

    private function createLocator()
    {
        return new HierarchicalLocator('/tmp');
    }

    private function createThing()
    {
        $thing = $this->getMock('Asoc\Dadatata\Model\ThingInterface');
        $thing->expects($this->any())->method('getKey')->will($this->returnValue('123456789'));

        return $thing;
    }
}
 