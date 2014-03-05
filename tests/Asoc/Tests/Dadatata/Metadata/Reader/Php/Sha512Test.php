<?php

namespace Asoc\Tests\Dadatata\Metadata\Reader\Php;

use Asoc\Dadatata\Metadata\Reader\Php\Sha512;
use Asoc\Dadatata\Metadata\ReaderInterface;
use Asoc\Tests\Dadatata\BaseTestCase;

class Sha512Test extends BaseTestCase {

    public function testExtract() {
        $this->markSkippedIfNotAvailable();
        $tempfile = $this->createTempFile();

        $reader = $this->createReader();
        $result = $reader->extract($tempfile);

        $this->assertArrayHasKey(ReaderInterface::HASH_SHA512, $result);
        $this->assertEquals(hash_file('sha512', $tempfile), $result[ReaderInterface::HASH_SHA512]);
    }

    protected function markSkippedIfNotAvailable() {
        if(!in_array('sha512', hash_algos())) {
            $this->markTestSkipped('SHA512 hash algorithm not available');
        }
    }

    protected function createReader() {
        return new Sha512();
    }

} 