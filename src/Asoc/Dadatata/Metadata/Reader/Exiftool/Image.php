<?php

namespace Asoc\Dadatata\Metadata\Reader\Exiftool;

/*
//These killed my phpstorm so I used the FQDN below instead of use
use PHPExiftool\Driver\TagInterface;
use PHPExiftool\Reader;
*/
use Asoc\Dadatata\Metadata\ReaderInterface;
use Monolog\Logger;

class Image implements ReaderInterface {

    private static $map = [
        'PNG' => [
            'ImageWidth' => ReaderInterface::IMAGE_WIDTH,
            'ImageHeight' => ReaderInterface::IMAGE_HEIGHT
        ],
        'File' => [
            'ImageWidth' => ReaderInterface::IMAGE_WIDTH,
            'ImageHeight' => ReaderInterface::IMAGE_HEIGHT
        ],
        'EXIF' => [
            'XResolution' => ReaderInterface::IMAGE_X_RESOLUTION,
            'YResolution' => ReaderInterface::IMAGE_Y_RESOLUTION
        ]
    ];

    public function canHandle($mime)
    {
        return strpos($mime, 'image/') !== false;
    }

    public function extract($path)
    {
        $reader = \PHPExiftool\Reader::create(new Logger('ignore'));
        $metadata = $reader->files([$path])->first();
        $result = [];
        $faceTags = [];

        /** @var \PHPExiftool\Driver\Metadata\MetaData $meta */
        foreach ($metadata as $meta)
        {
            /** @var \PHPExiftool\Driver\TagInterface $tag */
            $tag = $meta->getTag();
            $groupName = $tag->getGroupName();
            $tagName = $tag->getName();
            $faceTags[$groupName] = true;

            // microsoft photo tag
            if($groupName === 'XMP-MP') {
                if($tagName === 'RegionRectangle') {
                    $faceTags['coordinates'] = $meta->getValue()->asString();
                }
                elseif($tagName === 'RegionPersonDisplayName') {
                    $faceTags['names'] = $meta->getValue()->asString();
                }

                continue;
            }

            if(!isset(static::$map[$groupName])) {
                continue;
            }
            $group = static::$map[$groupName];
            if(!isset($group[$tagName])) {
                continue;
            }

            $result[$group[$tagName]] = $meta->getValue()->asString();
        }
        
        if(isset($faceTags['coordinates']) && isset($faceTags['names'])) {
            $result[ReaderInterface::IMAGE_PEOPLE] = [];
            
            $coordinates = explode(';', $faceTags['coordinates']);
            $names = explode(';', $faceTags['names']);
            
            foreach($names as $name) {
                $name = trim($name);
                $personCoordinates = explode(',', array_pop($coordinates));

                if(count($personCoordinates) !== 4) {
                    continue;
                }

                $result[ReaderInterface::IMAGE_PEOPLE][$name] = $personCoordinates;
            }
        }

        return $result;
    }

}