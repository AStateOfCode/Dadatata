<?php

namespace Asoc\Dadatata\Metadata\Reader\Php;

use Asoc\Dadatata\Metadata\ReaderInterface;

class Sha512 implements ReaderInterface {

    public function canHandle($mime)
    {
        return true;
    }

    public function extract($path)
    {
        $result = [];
        $result[ReaderInterface::HASH_SHA512] = hash_file('sha512', $path);
        return $result;
    }
}