<?php

namespace Asoc\Dadatata\Metadata;

/**
 * Interface ExaminerInterface
 *
 * @package Asoc\Dadatata\Metadata
 */
interface ExaminerInterface
{
    /**
     * Reads file metadata. File size and mime type are always retrieved.
     *
     * @param \SplFileInfo|string $fileOrPath File
     * @param string|null         $mime       MIME hint, prevents re-read of the mime type.
     *
     * @return array Index 0 = array of meta info, Index 1 = mime type
     */
    public function examine($fileOrPath, $mime = null);

    /**
     * Groups the file into a common category, eg. image or document.
     *
     * @param \SplFileInfo|string $fileOrPath File
     * @param string|null         $mime       MIME hint, prevents re-read of the mime type.
     *
     * @return array Index 0 = category, Index 1 = mime type
     */
    public function categorize($fileOrPath, $mime = null);
}