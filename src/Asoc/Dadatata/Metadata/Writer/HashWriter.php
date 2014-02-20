<?php

namespace Asoc\Dadatata\Metadata\Writer;

use Asoc\Dadatata\Metadata\ReaderInterface;
use Asoc\Dadatata\Metadata\WriterInterface;
use Asoc\Dadatata\Model\ThingInterface;

class HashWriter implements WriterInterface {

    public function canHandle($object)
    {
        return $object instanceof ThingInterface;
    }

    /**
     * @param ThingInterface $object
     * @param array $knowledge
     */
    public function apply($object, array $knowledge)
    {
        if(isset($knowledge[ReaderInterface::HASH_MD5])) {
            $object->setHash(ReaderInterface::HASH_MD5, $knowledge[ReaderInterface::HASH_MD5]);
        }

        if(isset($knowledge[ReaderInterface::HASH_SHA1])) {
            $object->setHash(ReaderInterface::HASH_SHA1, $knowledge[ReaderInterface::HASH_SHA1]);
        }

        if(isset($knowledge[ReaderInterface::HASH_SHA512])) {
            $object->setHash(ReaderInterface::HASH_SHA512, $knowledge[ReaderInterface::HASH_SHA512]);
        }
    }

}