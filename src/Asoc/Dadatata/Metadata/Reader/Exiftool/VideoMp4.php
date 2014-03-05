<?php

namespace Asoc\Dadatata\Metadata\Reader\Exiftool;

use Asoc\Dadatata\Metadata\ReaderInterface;
use PHPExiftool\Driver\Value\ValueInterface;

class VideoMp4 extends BaseReader {

    private static $map = [
        'Track1' => [
            'TrackDuration' => ReaderInterface::VIDEO_DURATION,
            'ImageWidth' => ReaderInterface::VIDEO_WIDTH,
            'ImageHeight' => ReaderInterface::VIDEO_HEIGHT,
            'BitDepth' => ReaderInterface::VIDEO_BIT_DEPTH,
            'AverageBitrate' => ReaderInterface::VIDEO_BITRATE,
            'VideoFrameRate' => ReaderInterface::VIDEO_FRAMERATE
        ]
    ];

    public function canHandle($mime)
    {
        return $mime === 'video/mp4';
    }

    protected function getMap()
    {
        return static::$map;
    }

    protected function getValue($group, $tag, ValueInterface $value)
    {
        switch($tag) {
            case 'TrackDuration':
                return $this->parseDuration($value->asString());
            case 'ImageWidth':
            case 'ImageHeight':
            case 'BitDepth':
            case 'VideoFrameRate':
                return intval($value->asString());
            case 'AverageBitrate':
                return floatval($value->asString());
        }

        return null;
    }
}