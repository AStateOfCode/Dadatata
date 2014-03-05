<?php

namespace Asoc\Tests\Dadatata\Metadata\Reader\Php;

use Asoc\Dadatata\Metadata\Reader\Php\Imagine;
use Asoc\Dadatata\Metadata\ReaderInterface;
use Asoc\Tests\Dadatata\BaseTestCase;

class ImagineTest extends BaseTestCase {

    public function testExtract() {
        $this->markSkippedIfNotAvailable();
        $reader = $this->createReader();

        $result = $reader->extract('dontcare');

        $this->assertArrayHasKey(ReaderInterface::IMAGE_WIDTH, $result);
        $this->assertArrayHasKey(ReaderInterface::IMAGE_HEIGHT, $result);
        $this->assertEquals(1337, $result[ReaderInterface::IMAGE_WIDTH]);
        $this->assertEquals(42, $result[ReaderInterface::IMAGE_HEIGHT]);
    }

    protected function markSkippedIfNotAvailable() {
        if(!interface_exists('Imagine\Image\ImagineInterface')) {
            $this->markTestSkipped('Imagine library not available');
        }
    }

    protected function createReader() {
        $size = $this->getMock('Imagine\Image\BoxInterface');
        $size->expects($this->any())->method('getWidth')->will($this->returnValue(1337));
        $size->expects($this->any())->method('getHeight')->will($this->returnValue(42));

        $image = $this->getMock('Imagine\Image\ImageInterface');
        $image->expects($this->any())->method('getSize')->will($this->returnValue($size));

        $imagine = $this->getMock('Imagine\Image\ImagineInterface');
        $imagine->expects($this->any())->method('open')->will($this->returnValue($image));

        return new Imagine($imagine);
    }

} 