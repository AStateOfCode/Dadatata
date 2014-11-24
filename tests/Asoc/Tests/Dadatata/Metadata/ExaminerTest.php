<?php

namespace Asoc\Tests\Dadatata\Metadata;

use Asoc\Dadatata\Metadata\Examiner;
use Asoc\Dadatata\Metadata\ReaderInterface;
use Asoc\Dadatata\Metadata\TypeGuesserInterface;
use Asoc\Tests\Dadatata\BaseTestCase;

class ExaminerTest extends BaseTestCase
{
    public function testExamine()
    {
        $path = $this->createLocator()->getFilePath($this->createImageThingMock());

        $typeGuesser = $this->createTypeGuesser('dontcare', 'image/png');

        $reader1 = $this->createReader(
            true,
            [
                'foo' => 42
            ]
        );
        $reader2 = $this->createReader(
            false,
            [
                'test' => 'ing'
            ]
        );
        $reader3 = $this->createReader(
            true,
            [
                'bar' => 1337
            ]
        );
        $reader4 = $this->createReader(
            true,
            [
                'bar' => 1338
            ]
        );

        $examiner = new Examiner([$typeGuesser], [$reader1, $reader2, $reader3, $reader4]);
        list($knowledge, $mime) = $examiner->examine($path);

        $this->assertEquals('image/png', $mime);
        $this->assertArrayHasKey('foo', $knowledge);
        $this->assertArrayNotHasKey('test', $knowledge);
        $this->assertArrayHasKey('bar', $knowledge);
        $this->assertArrayHasKey(ReaderInterface::SIZE, $knowledge, 'The size metadata should always be present');
        $this->assertEquals(42, $knowledge['foo']);
        $this->assertEquals(1338, $knowledge['bar'], 'Last reader should overwrite result from previous reader');
        $this->assertEquals(filesize($path), $knowledge[ReaderInterface::SIZE]);
    }

    public function testCategorizeWithNoTypeGuesser()
    {
        $path = $this->createLocator()->getFilePath($this->createImageThingMock());

        $examiner = new Examiner([], []);
        list($category, $mime) = $examiner->categorize($path);

        $this->assertNull($category);
        $this->assertNull($mime);
    }

    public function testCategorizeWithOneTypeGuesser()
    {
        $path = $this->createLocator()->getFilePath($this->createImageThingMock());

        $expectedCategory = TypeGuesserInterface::CATEGORY_IMAGE;
        $expectedMime     = 'image/png';

        $typeGuesser = $this->createTypeGuesser($expectedCategory, $expectedMime);

        $examiner = new Examiner([$typeGuesser], []);
        list($category, $mime) = $examiner->categorize($path);

        $this->assertEquals($expectedCategory, $category);
        $this->assertEquals($expectedMime, $mime);
    }

    public function testCategorizeWithMultipleTypeGuessers()
    {
        $path = $this->createLocator()->getFilePath($this->createImageThingMock());

        $expectedCategory = TypeGuesserInterface::CATEGORY_IMAGE;
        $expectedMime     = 'image/png';

        $typeGuesser1 = $this->createTypeGuesser($expectedCategory, $expectedMime);
        $typeGuesser2 = $this->createTypeGuesser('wrongcategory', 'wrongmime');
        $typeGuesser3 = $this->createTypeGuesser($expectedCategory, $expectedMime);

        $examiner = new Examiner([$typeGuesser1, $typeGuesser2, $typeGuesser3]);
        list($category, $mime) = $examiner->categorize($path);

        $this->assertEquals($expectedCategory, $category);
        $this->assertEquals($expectedMime, $mime);
    }

    protected function createTypeGuesser($category, $mime)
    {
        $typeGuesser = $this->getMock('Asoc\Dadatata\Metadata\TypeGuesserInterface');
        $typeGuesser->expects($this->any())->method('categorize')->will($this->returnValue($category));
        $typeGuesser->expects($this->any())->method('getMimeType')->will($this->returnValue($mime));

        return $typeGuesser;
    }

    protected function createReader($canHandle = true, $extractValue = null)
    {
        $reader = $this->getMock('Asoc\Dadatata\Metadata\ReaderInterface');
        $reader->expects($this->any())->method('canHandle')->will($this->returnValue($canHandle));
        $reader->expects($this->any())->method('extract')->will($this->returnValue($extractValue));

        return $reader;
    }
}
