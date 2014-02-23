<?php

namespace Asoc\Dadatata\Metadata;

class MimeTypeGuesser implements TypeGuesserInterface {

    public function categorize($path, $mime = null)
    {
        if($mime === null) {
            $mime = $this->getMimeType($path);
        }

        $mime = strtolower($mime);

        switch (true) {
            case strpos($mime, 'image/') === 0:
            case isset(static::$mimeCategoryMapImage[$mime]):
                return self::CATEGORY_IMAGE;

            case strpos($mime, 'video/') === 0:
            case isset(static::$mimeCategoryMapVideo[$mime]):
                return self::CATEGORY_VIDEO;

            case strpos($mime, 'audio/') === 0:
            case isset(static::$mimeCategoryMapAudio[$mime]):
                return self::CATEGORY_AUDIO;

            case strpos($mime, 'text/') === 0:
            case isset(static::$mimeCategoryMapText[$mime]):
                return self::CATEGORY_TEXT;

            case isset(static::$mimeCategoryMapDocument[$mime]):
                return self::CATEGORY_DOCUMENT;
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

    public static $mimeCategoryMapImage = [
        'application/postscript',
        'application/illustrator'
    ];

    public static $mimeCategoryMapAudio = [
        'application/ogg'
    ];

    public static $mimeCategoryMapVideo = [
        'application/vnd.rn-realmedia'
    ];

    public static $mimeCategoryMapText = [
        'application/x-php'
    ];

    public static $mimeCategoryMapDocument = [
        'application/msword',
        'application/access',
        'application/pdf',
        'application/excel',
        'application/powerpoint',
        'application/vnd.ms-powerpoint',
        'application/vnd.ms-excel',
        'application/vnd.oasis.opendocument.formula',
        'application/vnd.oasis.opendocument.text-master',
        'application/vnd.oasis.opendocument.database',
        'application/vnd.oasis.opendocument.chart',
        'application/vnd.oasis.opendocument.graphics',
        'application/vnd.oasis.opendocument.presentation',
        'application/vnd.oasis.opendocument.speadsheet',
        'application/vnd.oasis.opendocument.text'
    ];

}