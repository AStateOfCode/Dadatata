<?php

namespace Asoc\Dadatata\Metadata\Writer;

use Asoc\Dadatata\Metadata\ReaderInterface;
use Asoc\Dadatata\Metadata\WriterInterface;
use Asoc\Dadatata\Model\ThingInterface;
use Asoc\Dadatata\Model\VideoInterface;

class VideoWriter implements WriterInterface
{
    public function canHandle($object)
    {
        return $object instanceof VideoInterface;
    }

    /**
     * @param ThingInterface|VideoInterface $object
     * @param array                         $knowledge
     */
    public function apply($object, array $knowledge)
    {
        if (isset($knowledge[ReaderInterface::VIDEO_WIDTH])) {
            $width = intval($knowledge[ReaderInterface::VIDEO_WIDTH]);
            $object->setWidth($width);
        }
        if (isset($knowledge[ReaderInterface::VIDEO_HEIGHT])) {
            $height = intval($knowledge[ReaderInterface::VIDEO_HEIGHT]);
            $object->setHeight($height);
        }
        if (isset($knowledge[ReaderInterface::VIDEO_BITRATE])) {
            $object->setBitrate($knowledge[ReaderInterface::VIDEO_BITRATE]);
        }
        if (isset($knowledge[ReaderInterface::VIDEO_CODEC])) {
            $object->setCodec($knowledge[ReaderInterface::VIDEO_CODEC]);
        }
//        if(isset($knowledge[ReaderInterface::VIDEO_FRAMERATE])) {
//            $object->setFramerate($knowledge[ReaderInterface::VIDEO_FRAMERATE]);
//        }
    }
}