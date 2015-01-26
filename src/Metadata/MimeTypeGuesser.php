<?php

namespace Asoc\Dadatata\Metadata;

class MimeTypeGuesser implements TypeGuesserInterface
{
    public function categorize($path, $mime = null)
    {
        if ($mime === null) {
            $mime = $this->getMimeType($path);
        }

        $mime = strtolower($mime);

        switch (true) {
            case isset(static::$mimeCategoryMapImage[$mime]):
                return self::CATEGORY_IMAGE;

            case isset(static::$mimeCategoryMapVideo[$mime]):
                return self::CATEGORY_VIDEO;

            case isset(static::$mimeCategoryMapAudio[$mime]):
                return self::CATEGORY_AUDIO;

            case isset(static::$mimeCategoryMapText[$mime]):
                return self::CATEGORY_TEXT;

            case isset(static::$mimeCategoryMapArchive[$mime]):
                return self::CATEGORY_ARCHIVE;

            case isset(static::$mimeCategoryMapDocument[$mime]):
                return self::CATEGORY_DOCUMENT;

            case strpos($mime, 'text/') === 0:
                return self::CATEGORY_TEXT;

            case strpos($mime, 'image/') === 0:
                return self::CATEGORY_IMAGE;

            case strpos($mime, 'audio/') === 0:
                return self::CATEGORY_AUDIO;

            case strpos($mime, 'video/') === 0:
                return self::CATEGORY_VIDEO;
        }

        return null;
    }

    public function getMimeType($path)
    {
        try {
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mime  = $finfo->file($path);

            return $mime;
        } catch (\Exception $e) {
            /* intentionally silenced */
        }

        return null;
    }

    // http://en.wikipedia.org/wiki/List_of_archive_formats
    public static $mimeCategoryMapArchive = [
        'application/x-cpio'                      => true,
        'application/x-shar'                      => true,
        'application/x-tar'                       => true,
        'application/x-bzip2'                     => true,
        'application/x-gzip'                      => true,
        'application/x-lzip'                      => true,
        'application/x-lzma'                      => true,
        'application/x-lzop'                      => true,
        'application/x-xz'                        => true,
        'application/x-compress'                  => true,
        'application/x-7z-compressed'             => true,
        'application/x-ace-compressed'            => true,
        'application/x-alz-compressed'            => true,
        'application/vnd.android.package-archive' => true,
        'application/x-arj'                       => true,
        'application/vnd.ms-cab-compressed'       => true,
        'application/x-cfs-compressed'            => true,
        'application/x-dar'                       => true,
        'application/x-dgc-compressed'            => true,
        'application/x-apple-diskimage'           => true,
        'application/x-gca-compressed'            => true,
        'application/x-lzh'                       => true,
        'application/x-lzx'                       => true,
        'application/x-rar-compressed'            => true,
        'application/x-stuffit'                   => true,
        'application/x-stuffitx'                  => true,
        'application/x-gtar'                      => true,
        'application/zip'                         => true,
        'application/x-zoo'                       => true
    ];

    public static $mimeCategoryMapImage = [
        'application/postscript'  => true,
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
        'application/pdf'                                                           => true,
        // open documents
        'application/vnd.oasis.opendocument.formula'                                => true,
        'application/vnd.oasis.opendocument.text-master'                            => true,
        'application/vnd.oasis.opendocument.database'                               => true,
        'application/vnd.oasis.opendocument.chart'                                  => true,
        'application/vnd.oasis.opendocument.graphics'                               => true,
        'application/vnd.oasis.opendocument.presentation'                           => true,
        'application/vnd.oasis.opendocument.spreadsheet'                            => true,
        'application/vnd.oasis.opendocument.text'                                   => true,
        // microsoft office <2007
        'application/msword'                                                        => true,
        'application/access'                                                        => true,
        'application/excel'                                                         => true,
        'application/powerpoint'                                                    => true,
        'application/vnd.ms-powerpoint'                                             => true,
        'application/vnd.ms-excel'                                                  => true,
        // http://technet.microsoft.com/en-us/library/ee309278%28office.12%29.aspx
        // microsoft office >=2007
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'   => true,
        'application/vnd.openxmlformats-officedocument.wordprocessingml.template'   => true,
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'         => true,
        'application/vnd.openxmlformats-officedocument.spreadsheetml.template'      => true,
        'application/vnd.openxmlformats-officedocument.presentationml.presentation' => true,
        'application/vnd.openxmlformats-officedocument.presentationml.template'     => true,
        'text/rtf'                                                                  => true,
        'application/rtf'                                                           => true
    ];

}
