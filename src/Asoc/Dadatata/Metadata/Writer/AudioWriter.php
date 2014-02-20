<?php

namespace Asoc\Dadatata\Metadata\Writer;

use Asoc\Dadatata\Metadata\ReaderInterface;
use Asoc\Dadatata\Metadata\WriterInterface;
use Asoc\Dadatata\Model\AudioInterface;
use Asoc\Dadatata\Model\ThingInterface;

class AudioWriter implements WriterInterface {

    public function canHandle($object)
    {
        return $object instanceof AudioInterface;
    }

    /**
     * @param ThingInterface|AudioInterface $object
     * @param array $knowledge
     */
    public function apply($object, array $knowledge)
    {
        if(isset($knowledge[ReaderInterface::AUDIO_LENGTH])) {
            $object->setDuration($knowledge[ReaderInterface::AUDIO_LENGTH]);
        }
        if(isset($knowledge[ReaderInterface::AUDIO_ALBUM])) {
            $object->setAlbum($knowledge[ReaderInterface::AUDIO_ALBUM]);
        }
        if(isset($knowledge[ReaderInterface::AUDIO_ARTIST])) {
            $object->setArtist($knowledge[ReaderInterface::AUDIO_ARTIST]);
        }
        if(isset($knowledge[ReaderInterface::AUDIO_TITLE])) {
            $object->setTitle($knowledge[ReaderInterface::AUDIO_TITLE]);
        }
        if(isset($knowledge[ReaderInterface::AUDIO_SAMPLE_RATE])) {
            $object->setSamplingRate($knowledge[ReaderInterface::AUDIO_SAMPLE_RATE]);
        }
        if(isset($knowledge[ReaderInterface::AUDIO_CHANNELS])) {
            $object->setChannels($knowledge[ReaderInterface::AUDIO_CHANNELS]);
        }
        if(isset($knowledge[ReaderInterface::AUDIO_BITRATE])) {
            $object->setBitrate($knowledge[ReaderInterface::AUDIO_BITRATE]);
        }
    }

}