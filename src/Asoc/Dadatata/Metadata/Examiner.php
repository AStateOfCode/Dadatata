<?php

namespace Asoc\Dadatata\Metadata;

use Asoc\Dadatata\Exception\FileNotFoundException;

class Examiner implements ExaminerInterface {

    /**
     * @var ReaderInterface[]
     */
    private $reader;
    /**
     * @var TypeGuesserInterface[]
     */
    private $typeGuesser;

    public function __construct(array $typeGuesser, array $reader = []) {
        $this->typeGuesser = $typeGuesser;
        $this->reader = $reader;
    }

    public function examine($fileOrPath, $mime = null) {
        $knowledge = [];

        if($fileOrPath instanceof \SplFileInfo) {
            $path = $fileOrPath->getPathname();
        }
        else {
            $path = $fileOrPath;
        }

        if(!file_exists($path)) {
            throw new FileNotFoundException(sprintf('Does not exist: %s', $path));
        }

        if($mime === null) {
            list($_ignore, $mime) = $this->categorize($path);
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

    public function categorize($fileOrPath, $mime = null)
    {
        // TODO mime weight
        $categories = [];

        if($fileOrPath instanceof \SplFileInfo) {
            $path = $fileOrPath->getPathname();
        }
        else {
            $path = $fileOrPath;
        }

        if(!file_exists($path)) {
            throw new FileNotFoundException(sprintf('Does not exist: %s', $path));
        }

        foreach($this->typeGuesser as $guesser) {
            if($mime === null) {
                $mime = $guesser->getMimeType($path);
            }

            $category = $guesser->categorize($path, $mime);
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

}