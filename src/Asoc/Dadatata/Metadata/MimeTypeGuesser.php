<?php

namespace Asoc\Dadatata\Metadata;

class MimeTypeGuesser implements TypeGuesserInterface {

    public function categorize($path, $mime = null)
    {
        if($mime === null) {
            $mime = $this->getMimeType($path);
        }

        $mime = strtolower($mime);

        // initially taken from
        // https://github.com/romainneutron/MediaVorus/blob/master/src/MediaVorus/MediaVorus.php
        switch (true) {
            case strpos($mime, 'image/') === 0:
            case $mime === 'application/postscript':
            case $mime === 'application/illustrator':
                return self::CATEGORY_IMAGE;

            case strpos($mime, 'video/') === 0:
            case $mime === 'application/vnd.rn-realmedia':
                return self::CATEGORY_VIDEO;

            case strpos($mime, 'audio/') === 0:
                return self::CATEGORY_AUDIO;

            case strpos($mime, 'text/') === 0:
            case $mime === 'application/msword':
            case $mime === 'application/access':
            case $mime === 'application/pdf':
            case $mime === 'application/excel':
            case $mime === 'application/powerpoint':
            case $mime === 'application/vnd.ms-powerpoint':
            case $mime === 'application/vnd.ms-excel':
            case $mime === 'application/vnd.oasis.opendocument.formula':
            case $mime === 'application/vnd.oasis.opendocument.text-master':
            case $mime === 'application/vnd.oasis.opendocument.database':
            case $mime === 'application/vnd.oasis.opendocument.chart':
            case $mime === 'application/vnd.oasis.opendocument.graphics':
            case $mime === 'application/vnd.oasis.opendocument.presentation':
            case $mime === 'application/vnd.oasis.opendocument.speadsheet':
            case $mime === 'application/vnd.oasis.opendocument.text':
                return self::CATEGORY_DOCUMENT;

            default:
                break;
        }

        return null;
    }

    public function getMimeType($path)
    {
        try {
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($path);
            return $mime;
        }
        catch(\Exception $e) {
            /* intentionally silenced */
        }

        return null;
    }
}