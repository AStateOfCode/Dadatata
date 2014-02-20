<?php


namespace Asoc\Dadatata\Metadata\Reader\Exiftool;


use Asoc\Dadatata\Metadata\ReaderInterface;
use Monolog\Logger;
use PHPExiftool\Driver\Value\ValueInterface;

abstract class BaseReader implements ReaderInterface {

    abstract protected function getMap();

    abstract protected function getValue($group, $tag, ValueInterface $value);

    protected function parseBitrate($value) {
        if(preg_match('/(\d+)/', $value, $matches) === 1) {
            return floatval($matches[1]);
        }
        return null;
    }

    protected function parseDuration($value) {
        if(preg_match('/(\d+):(\d+):(\d+)/', $value, $matches) === 1) {
            $length = 0;
            $length += intval($matches[1]) * 60 * 60; // hours
            $length += intval($matches[2]) * 60; // minutes
            $length += intval($matches[3]); // seconds
            return $length;
        }
        return null;
    }

    public function extract($path)
    {
        $reader = \PHPExiftool\Reader::create(new Logger('ignore'));
        $metadata = $reader->files([$path])->first();
        $result = [];
        $map = $this->getMap();
        $foo = $metadata->getMetadatas();

        /** @var \PHPExiftool\Driver\Metadata\MetaData $meta */
        foreach ($metadata as $meta)
        {
            /** @var \PHPExiftool\Driver\TagInterface $tag */
            $tag = $meta->getTag();
            $groupName = $tag->getGroupName();
            $tagName = $tag->getName();

            if(!isset($map[$groupName])) {
                continue;
            }
            $group = $map[$groupName];
            if(!isset($group[$tagName])) {
                continue;
            }

            $value = $this->getValue($groupName, $tagName, $meta->getValue());

            $result[$group[$tagName]] = $value;
        }

        return $result;
    }

}