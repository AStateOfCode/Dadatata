<?php

namespace Asoc\Dadatata\Metadata\Reader\Exiftool;

use Asoc\Dadatata\Metadata\ReaderInterface;
use PHPExiftool\Driver\Value\ValueInterface;

class AudioMpeg extends BaseAudioReader {

    private static $map = [
        'MPEG' => [
            'SampleRate' => ReaderInterface::AUDIO_SAMPLE_RATE,
            'AudioBitrate' => ReaderInterface::AUDIO_BITRATE,
        ],
        'ID3v2_3' => [
            'Artist' => ReaderInterface::AUDIO_ARTIST,
            'Title' => ReaderInterface::AUDIO_TITLE,
            'Album' => ReaderInterface::AUDIO_ALBUM,
            'Track' => ReaderInterface::AUDIO_TRACK_NUM,
            'Year' => ReaderInterface::AUDIO_YEAR
        ],
        'Composite' => [
            'Duration' => ReaderInterface::AUDIO_LENGTH
        ]
    ];

    public function canHandle($mime)
    {
        return $mime === 'audio/mpeg';
    }

    protected function getValue($group, $tag, ValueInterface $value)
    {
        switch($tag) {
            case 'SampleRate':
                return intval($value->asString());
            case 'AudioBitrate':
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