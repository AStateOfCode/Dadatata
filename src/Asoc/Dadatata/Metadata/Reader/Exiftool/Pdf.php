<?php

namespace Asoc\Dadatata\Metadata\Reader\Exiftool;

/*
//These killed my phpstorm so I used the FQDN below instead of use
use PHPExiftool\Driver\TagInterface;
use PHPExiftool\Reader;
*/
use Asoc\Dadatata\Metadata\ReaderInterface;
use Monolog\Logger;

class Pdf implements ReaderInterface {

    private static $map = [
        'PDF' => [
            'PageCount' => ReaderInterface::DOCUMENT_PAGE_COUNT
            //'Linearized',
            //'PDFVersion',
            //'Producer'
        ]
    ];

    public function canHandle($mime)
    {
        return $mime === 'application/pdf';
    }

    public function extract($path)
    {
        $reader = \PHPExiftool\Reader::create(new Logger('ignore'));
        $metadata = $reader->files([$path])->first();
        $result = [];

        /** @var \PHPExiftool\Driver\Metadata\MetaData $meta */
        foreach ($metadata as $meta)
        {
            /** @var \PHPExiftool\Driver\TagInterface $tag */
            $tag = $meta->getTag();
            $groupName = $tag->getGroupName();
            $tagName = $tag->getName();
            $faceTags[$groupName] = true;

            if(!isset(static::$map[$groupName])) {
                continue;
            }
            $group = static::$map[$groupName];
            if(!isset($group[$tagName])) {
                continue;
            }

            $result[$group[$tagName]] = $meta->getValue()->asString();
        }

        return $result;
    }

}