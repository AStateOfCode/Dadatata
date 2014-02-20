<?php

namespace Asoc\Dadatata\Metadata\Writer;

use Asoc\Dadatata\Metadata\ReaderInterface;
use Asoc\Dadatata\Metadata\WriterInterface;
use Asoc\Dadatata\Model\ImageInterface;
use Asoc\Dadatata\Model\ThingInterface;

class ImageWriter implements WriterInterface {

    public function canHandle($object)
    {
        return $object instanceof ImageInterface;
    }

    /**
     * @param ThingInterface|ImageInterface $object
     * @param array $knowledge
     */
    public function apply($object, array $knowledge)
    {
        if(isset($knowledge[ReaderInterface::IMAGE_WIDTH])) {
            $width = intval($knowledge[ReaderInterface::IMAGE_WIDTH]);
            $object->setWidth($width);
        }
        if(isset($knowledge[ReaderInterface::IMAGE_HEIGHT])) {
            $height = intval($knowledge[ReaderInterface::IMAGE_HEIGHT]);
            $object->setHeight($height);
        }
        if(isset($knowledge[ReaderInterface::IMAGE_FORMAT])) {
            $object->setFormat($knowledge[ReaderInterface::IMAGE_FORMAT]);
        }
    }

}