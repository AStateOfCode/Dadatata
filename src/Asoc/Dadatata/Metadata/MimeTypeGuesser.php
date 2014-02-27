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
        'application/postscript' => true,
        'application/illustrator' => true
    ];

    public static $mimeCategoryMapAudio = [
        'application/ogg' => true
    ];

    public static $mimeCategoryMapVideo = [
        'application/vnd.rn-realmedia' => true
    ];

    public static $mimeCategoryMapText = [
        'application/x-php' => true
    ];

    public static $mimeCategoryMapDocument = [
        'application/pdf' => true,

        // open documents
        'application/vnd.oasis.opendocument.formula' => true,
        'application/vnd.oasis.opendocument.text-master' => true,
        'application/vnd.oasis.opendocument.database' => true,
        'application/vnd.oasis.opendocument.chart' => true,
        'application/vnd.oasis.opendocument.graphics' => true,
        'application/vnd.oasis.opendocument.presentation' => true,
        'application/vnd.oasis.opendocument.speadsheet' => true,
        'application/vnd.oasis.opendocument.text' => true,

        // microsoft office <2007
        'application/msword' => true,
        'application/access' => true,
        'application/excel' => true,
        'application/powerpoint' => true,
        'application/vnd.ms-powerpoint' => true,
        'application/vnd.ms-excel' => true,

        // microsoft office >=2007
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => true,
        'application/vnd.openxmlformats-officedocument.wordprocessingml.template' => true,
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => true,
        'application/vnd.openxmlformats-officedocument.spreadsheetml.template' => true,
        'application/vnd.openxmlformats-officedocument.presentationml.presentation' => true,
        'application/vnd.openxmlformats-officedocument.presentationml.template' => true
    ];

}