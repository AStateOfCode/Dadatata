<?php

namespace Asoc\Dadatata\Metadata;

class ExtensionTypeGuesser implements TypeGuesserInterface {

    // initiallty taken from
    // https://github.com/romainneutron/MediaVorus
    public static $extMimeMap = array(
        'ape' => 'audio/x-monkeys-audio',
        'mp3' => 'audio/mpeg',
        'eps' => 'application/postscript',
        'ai'  => 'application/illustrator',
        '3fr' => 'image/x-tika-hasselblad',
        'arw' => 'image/x-tika-sony',
        'bay' => 'image/x-tika-casio',
        'cap' => 'image/x-tika-phaseone',
        'cr2' => 'image/x-tika-canon',
        'crw' => 'image/x-tika-canon',
        'dcs' => 'image/x-tika-kodak',
        'dcr' => 'image/x-tika-kodak',
        'dng' => 'image/x-tika-dng',
        'drf' => 'image/x-tika-kodak',
        'erf' => 'image/x-tika-epson',
        'fff' => 'image/x-tika-imacon',
        'iiq' => 'image/x-tika-phaseone',
        'kdc' => 'image/x-tika-kodak',
        'k25' => 'image/x-tika-kodak',
        'mef' => 'image/x-tika-mamiya',
        'mos' => 'image/x-tika-leaf',
        'mrw' => 'image/x-tika-minolta',
        'nef' => 'image/x-tika-nikon',
        'nrw' => 'image/x-tika-nikon',
        'orf' => 'image/x-tika-olympus',
        'pef' => 'image/x-tika-pentax',
        'ppm' => 'image/x-portable-pixmap',
        'ptx' => 'image/x-tika-pentax',
        'pxn' => 'image/x-tika-logitech',
        'raf' => 'image/x-tika-fuji',
        'raw' => 'image/x-tika-panasonic',
        'r3d' => 'image/x-tika-red',
        'rw2' => 'image/x-tika-panasonic',
        'rwz' => 'image/x-tika-rawzor',
        'sr2' => 'image/x-tika-sony',
        'srf' => 'image/x-tika-sony',
        'x3f' => 'image/x-tika-sigma',
        'webm' => 'video/webm',
        'ogv'  => 'video/ogg',
        'mts'  => 'video/m2ts'
    );

    public function categorize($path, $mime = null)
    {
        $mimeGuesser = new MimeTypeGuesser();
        return $mimeGuesser->categorize($path, $this->getMimeType($path));
    }

    public function getMimeType($path)
    {
        $extension = $this->getExtension($path);

        if(isset(static::$extMimeMap[$extension])) {
            return static::$extMimeMap[$extension];
        }

        return null;
    }

    private function getExtension($path) {
        return strtolower(pathinfo($path, PATHINFO_EXTENSION));
    }
}