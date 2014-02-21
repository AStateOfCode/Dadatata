<?php

namespace Asoc\Dadatata\Metadata;


interface TypeGuesserInterface {

    const CATEGORY_IMAGE = 'image';
    const CATEGORY_AUDIO = 'audio';
    const CATEGORY_VIDEO = 'video';
    const CATEGORY_ARCHIVE = 'archive';
    const CATEGORY_DOCUMENT = 'document';
    const CATEGORY_TEXT = 'text';

    public function categorize($path, $mime = null);

    public function getMimeType($path);

} 