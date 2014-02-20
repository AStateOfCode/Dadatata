<?php


namespace Asoc\Dadatata\Metadata\Reader\Exiftool;


use Asoc\Dadatata\Metadata\ReaderInterface;

abstract class BaseAudioReader extends BaseReader {

    public function getCategory()
    {
        return ReaderInterface::CATEGORY_AUDIO;
    }

} 