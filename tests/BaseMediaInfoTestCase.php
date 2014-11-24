<?php

namespace Asoc\Dadatata\Tests;

abstract class BaseMediaInfoTestCase extends BaseTestCase
{
    protected $mediainfoPath;

    protected function setUp()
    {
        $this->mediainfoPath = $this->skipIfToolIsNotAvailable('mediainfo', 'MediaInfo');

        parent::setUp();
    }
}