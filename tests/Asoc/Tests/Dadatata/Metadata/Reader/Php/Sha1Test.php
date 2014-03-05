<?php

namespace Asoc\Tests\Dadatata\Metadata\Reader\Php;

use Asoc\Dadatata\Metadata\Reader\Php\Sha1;
use Asoc\Dadatata\Metadata\ReaderInterface;
use Asoc\Tests\Dadatata\BaseTestCase;

class Sha1Test extends BaseTestCase {

    public function testExtract() {
        $this->markSkippedIfNotAvailable();
        $tempfile = $this->createTempFile();

        $reader = $this->createReader();
        $result = $reader->extract($tempfile);

        $this->assertArrayHasKey(ReaderInterface::HASH_SHA1, $result);
        $this->assertEquals(hash_file('sha1', $tempfile), $result[ReaderInterface::HASH_SHA1]);
    }

    protected function markSkippedIfNotAvailable() {
        if(!in_array('sha1', hash_algos())) {
            $this->markTestSkipped('SHA1 hash algorithm not available');
        }
    }

    protected function createReader() {
        return new Sha1();
    }

} 