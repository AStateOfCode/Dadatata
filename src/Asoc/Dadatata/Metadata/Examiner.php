<?php

namespace Asoc\Dadatata\Metadata;

class Examiner implements ExaminerInterface {

    /**
     * @var ReaderInterface[]
     */
    private $reader;

    public function __construct(array $reader) {
        $this->reader = $reader;
    }

    public function examine($path, $mime = null) {
        $knowledge = [];

        if($mime === null) {
            $mime = $this->getMimeFromPath($path);
        }
        $knowledge[ReaderInterface::MIME] = $mime;
        $knowledge[ReaderInterface::SIZE] = filesize($path);

        foreach($this->reader as $reader) {
            if(!$reader->canHandle($mime)) {
                continue;
            }

            $data = $reader->extract($path);
            if($data !== null && is_array($data)) {
                $knowledge = array_merge($knowledge, $data);
            }
        }

        return [$knowledge, $mime];
    }

    public function categorize($data, $mime = null)
    {
        $categories = [];

        if($mime === null) {
            if(is_string($data) && ctype_print($data) === true && is_file($data)) {
                $path = realpath($data);
                if($path !== null && file_exists($path)) {
                    $mime = $this->getMimeFromPath($path);
                }
            }
            else if(is_object($data) && $data instanceof \SplFileInfo) {
                /** @var \SplFileInfo $data */
                $mime = $this->getMimeFromPath($data->getPathname());
            }
            else {
                $mime = $this->getMimeFromBlob($data);
            }
        }

        foreach($this->reader as $reader) {
            if(!$reader->canHandle($mime)) {
                continue;
            }

            $category = $reader->getCategory();
            if($category === null) {
                continue;
            }

            if(!isset($categories[$category])) {
                $categories[$category] = 1;
            }
            else {
                $categories[$category]++;
            }
        }

        if(count($categories) > 0) {
            arsort($categories);
            $category = array_keys($categories)[0];
        }
        else {
            $category = null;
        }

        return [$category, $mime];
    }

    private function getMimeFromBlob($data) {
        try {
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->buffer($data);
            return $mime;
        }
        catch(\Exception $e) {
            /* intentionally silenced */
        }

        return null;
    }

    private function getMimeFromPath($path)
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