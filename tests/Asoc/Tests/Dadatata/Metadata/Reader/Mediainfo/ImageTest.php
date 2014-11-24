<?php
namespace Asoc\Tests\Dadatata\Metadata\Reader\Mediainfo;

use Asoc\Dadatata\Metadata\Reader\Mediainfo\Image;
use Asoc\Tests\Dadatata\BaseMediaInfoTestCase;

class ImageTest extends BaseMediaInfoTestCase
{
    public function testExtract()
    {
        $thing  = $this->createImageThingMock();
        $path   = $this->createLocator()->getFilePath($thing);
        $reader = $this->setupReader();

        $result = $reader->extract($path);
        $this->assertArrayHasKey('width', $result);
        $this->assertArrayHasKey('height', $result);
        $this->assertArrayHasKey('bitdepth', $result);
        $this->assertEquals($result['width'], 1000);
        $this->assertEquals($result['height'], 530);
        $this->assertEquals($result['bitdepth'], 32);
    }

    private function setupReader()
    {
        return new Image($this->mediainfoPath);
    }
}
 