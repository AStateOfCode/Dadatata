<?php

namespace Asoc\Dadatata\Metadata\Reader\Exiftool;

/*
//These killed my phpstorm so I used the FQDN below instead of use
use PHPExiftool\Driver\TagInterface;
use PHPExiftool\Reader;
*/
use Asoc\Dadatata\Metadata\ReaderInterface;
use Monolog\Logger;

class VideoFlash implements ReaderInterface
{
    private static $map = [
        'Flash' => [
            'ImageWidth'    => ReaderInterface::VIDEO_WIDTH,
            'ImageHeight'   => ReaderInterface::VIDEO_HEIGHT,
            'TotalDuration' => ReaderInterface::VIDEO_DURATION,
            'VideoBitrate'  => ReaderInterface::VIDEO_BITRATE,
            'FrameRate'     => ReaderInterface::VIDEO_FRAMERATE,
            'VideoEncoding' => ReaderInterface::VIDEO_CODEC
        ]
    ];

    public function canHandle($mime)
    {
        return $mime === 'video/x-flv';
    }

    public function extract($path)
    {
        $reader   = \PHPExiftool\Reader::create(new Logger('ignore'));
        $metadata = $reader->files([$path])->first();
        $result   = [];

        /** @var \PHPExiftool\Driver\Metadata\MetaData $meta */
        foreach ($metadata as $meta) {
            /** @var \PHPExiftool\Driver\TagInterface $tag */
            $tag       = $meta->getTag();
            $groupName = $tag->getGroupName();
            $tagName   = $tag->getName();

            if (!isset(static::$map[$groupName])) {
                continue;
            }
            $group = static::$map[$groupName];
            if (!isset($group[$tagName])) {
                continue;
            }

            switch ($tagName) {
                case 'TotalDuration':
                    $value = floatval($meta->getValue()->asString());
                    break;
                case 'VideoBitrate':
                    preg_match('/(\d+)/', $meta->getValue()->asString(), $matches);
                    $value = floatval($matches[1]);
                    break;
                default:
                    $value = $meta->getValue()->asString();
                    break;
            }

            $result[$group[$tagName]] = $value;
        }

        return $result;
    }
}