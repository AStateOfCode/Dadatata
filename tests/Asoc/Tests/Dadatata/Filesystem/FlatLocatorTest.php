<?php

namespace Asoc\Tests\Dadatata\Filesystem;

use Asoc\Dadatata\Filesystem\FlatLocator;

class FlatLocatorTest extends \PHPUnit_Framework_TestCase {

    public function testGetRelativeDirectory() {
        $locator = $this->createLocator();
        $thing = $this->createThing();
        $this->assertEquals('', $locator->getRelativeDirectory($thing));
    }

    public function testGetDirectory() {
        $locator = $this->createLocator();
        $thing = $this->createThing();
        $this->assertEquals('/tmp', $locator->getDirectory($thing));
    }

    public function testGetRelativeFilePath() {
        $locator = $this->createLocator();
        $thing = $this->createThing();
        $this->assertEquals('123456789_1', $locator->getRelativeFilePath($thing));
    }

    public function testGetRelativeFilePathForFragmentTwo() {
        $locator = $this->createLocator();
        $thing = $this->createThing();
        $this->assertEquals('123456789_2', $locator->getRelativeFilePath($thing, 2));
    }

    public function testGetFilePath() {
        $locator = $this->createLocator();
        $thing = $this->createThing();
        $this->assertEquals('/tmp/123456789_1', $locator->getFilePath($thing));
    }

    public function testGetFilePathForFragmentTwo() {
        $locator = $this->createLocator();
        $thing = $this->createThing();
        $this->assertEquals('/tmp/123456789_2', $locator->getFilePath($thing, 2));
    }

    private function createLocator() {
        return new FlatLocator('/tmp');
    }

    private function createThing() {
        $thing = $this->getMock('Asoc\Dadatata\Model\ThingInterface');
        $thing->expects($this->any())->method('getKey')->will($this->returnValue('123456789'));
        return $thing;
    }

}
 