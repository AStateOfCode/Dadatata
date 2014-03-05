<?php

namespace Asoc\Tests\Dadatata\Metadata\Reader\Php;

use Asoc\Dadatata\Metadata\Reader\Php\Md5;
use Asoc\Dadatata\Metadata\ReaderInterface;
use Asoc\Tests\Dadatata\BaseTestCase;

class Md5Test extends BaseTestCase {

    public function testExtract() {
        $this->markSkippedIfNotAvailable();
        $tempfile = $this->createTempFile();

        $reader = $this->createReader();
        $result = $reader->extract($tempfile);

        $this->assertArrayHasKey(ReaderInterface::HASH_MD5, $result);
        $this->assertEquals(md5_file($tempfile), $result[ReaderInterface::HASH_MD5]);
    }

    protected function markSkippedIfNotAvailable() {
        if(!in_array('md5', hash_algos())) {
            $this->markTestSkipped('MD5 hash algorithm not available');
        }
    }

    protected function createReader() {
        return new Md5();
    }

} 