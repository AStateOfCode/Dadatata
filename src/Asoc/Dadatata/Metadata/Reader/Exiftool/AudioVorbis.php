<?php

namespace Asoc\Dadatata\Metadata\Reader\Exiftool;

use Asoc\Dadatata\Metadata\ReaderInterface;
use PHPExiftool\Driver\Value\ValueInterface;

class AudioVorbis extends BaseAudioReader {

    private static $map = [
        'Vorbis' => [
            'AudioChannels' => ReaderInterface::AUDIO_CHANNELS,
            'SampleRate' => ReaderInterface::AUDIO_SAMPLE_RATE,
            'NominalBitrate' => ReaderInterface::AUDIO_BITRATE,
            'Artist' => ReaderInterface::AUDIO_ARTIST,
            'Title' => ReaderInterface::AUDIO_TITLE,
            'Album' => ReaderInterface::AUDIO_ALBUM
        ],
        'Composite' => [
            'Duration' => ReaderInterface::AUDIO_LENGTH
        ]
    ];

    public function canHandle($mime)
    {
        return $mime === 'audio/x-ogg' || $mime === 'application/ogg' || $mime === 'audio/ogg';
    }

    protected function getValue($group, $tag, ValueInterface $value)
    {
        switch($tag) {
            case 'AudioChannels':
            case 'SampleRate':
                return intval($value->asString());
            case 'NominalBitrate':
                return $this->parseBitrate($value->asString());
            case 'Artist':
            case 'Title':
            case 'Album':
                return $value->asString();
            case 'Duration':
                return floatval($value->asString());
        }

        return null;
    }

    protected function getMap()
    {
        return static::$map;
    }
}