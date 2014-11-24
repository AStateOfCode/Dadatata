<?php

namespace Asoc\Dadatata\Metadata\Reader\Php;

use Asoc\Dadatata\Metadata\ReaderInterface;

class Md5 implements ReaderInterface
{
    public function canHandle($mime)
    {
        return true;
    }

    public function extract($path)
    {
        $result                            = [];
        $result[ReaderInterface::HASH_MD5] = md5_file($path);

        return $result;
    }
}