<?php

namespace Asoc\Dadatata\Metadata\Reader\Mediainfo;

use Asoc\Dadatata\Metadata\ReaderInterface;

class Image extends BaseReader
{
    public function canHandle($mime)
    {
        return strpos($mime, 'image/') !== false;
    }

    public function extract($path)
    {
        $mediaInfo = $this->getMediaInfo($path);

        if (!$this->isValid($mediaInfo)) {
            return null;
        }

        $track  = $mediaInfo->File->track[1];
        $result = [];

        if (($match = $this->match('/\d/', $track->Width)) !== null) {
            $result[ReaderInterface::IMAGE_WIDTH] = $match;
        }
        if (($match = $this->match('/\d/', $track->Height)) !== null) {
            $result[ReaderInterface::IMAGE_HEIGHT] = $match;
        }
        if (($match = $this->match('/\d/', $track->Bit_depth)) !== null) {
            $result[ReaderInterface::IMAGE_BIT_DEPTH] = $match;
        }
        if (isset($track->Color_space)) {
            $result[ReaderInterface::IMAGE_COLORSPACE] = (string)$track->Color_space;
        }

        return $result;
    }

    private function match($pattern, $value)
    {
        $value = (string)$value;

        if (strlen($value) === 0) {
            return null;
        }

        $matches = [];
        $result  = preg_match_all($pattern, $value, $matches);

        if ($result !== false && $result > 0) {
            return implode('', $matches[0]);
        }

        return null;
    }

    private function isValid($mediaInfo)
    {
        return isset($mediaInfo->File)
        && isset($mediaInfo->File->track)
        && $mediaInfo->File->track->count() > 1
        && (string)$mediaInfo->File->track[1]->attributes()['type'] === 'Image';
    }
}