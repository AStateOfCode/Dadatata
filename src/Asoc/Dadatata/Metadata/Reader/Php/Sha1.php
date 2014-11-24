<?php

namespace Asoc\Dadatata\Metadata\Reader\Php;

use Asoc\Dadatata\Metadata\ReaderInterface;

class Sha1 implements ReaderInterface
{
    public function canHandle($mime)
    {
        return true;
    }

    public function extract($path)
    {
        $result                             = [];
        $result[ReaderInterface::HASH_SHA1] = sha1_file($path);

        return $result;
    }
}