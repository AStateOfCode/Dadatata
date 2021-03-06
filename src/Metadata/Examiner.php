<?php

namespace Asoc\Dadatata\Metadata;

use Asoc\Dadatata\Exception\FileNotFoundException;

class Examiner implements ExaminerInterface
{
    /**
     * @var ReaderInterface[]
     */
    private $reader;
    /**
     * @var TypeGuesserInterface[]
     */
    private $typeGuesser;

    public function __construct(array $typeGuesser, array $reader = [])
    {
        $this->typeGuesser = $typeGuesser;
        $this->reader      = $reader;
    }

    public function examine($fileOrPath, $mime = null)
    {
        $knowledge = [];

        if ($fileOrPath instanceof \SplFileInfo) {
            $path = $fileOrPath->getPathname();
        } else {
            $path = $fileOrPath;
        }

        if (!file_exists($path)) {
            throw new FileNotFoundException(sprintf('Does not exist: %s', $path));
        }

        if ($mime === null) {
            list($_, $mime) = $this->categorize($path);
        }
        $knowledge[ReaderInterface::MIME] = $mime;
        $knowledge[ReaderInterface::SIZE] = filesize($path);

        foreach ($this->reader as $reader) {
            if (!$reader->canHandle($mime)) {
                continue;
            }

            // try to read the file, if it fails, don't break
            try {
                $data = $reader->extract($path);

                if ($data !== null && is_array($data)) {
                    $knowledge = array_merge($knowledge, $data);
                }
            } catch (\Exception $e) {
                // TODO log
            }
        }

        return [$knowledge, $mime];
    }

    public function categorize($fileOrPath, $mime = null)
    {
        $categories = [];
        $mimes      = [];

        if ($fileOrPath instanceof \SplFileInfo) {
            $path = $fileOrPath->getPathname();
        } else {
            $path = $fileOrPath;
        }

        if (!file_exists($path)) {
            throw new FileNotFoundException(sprintf('Does not exist: %s', $path));
        }

        foreach ($this->typeGuesser as $guesser) {
            $mime = $guesser->getMimeType($path);

            if (empty($mime)) {
                continue;
            }

            if (!isset($mimes[$mime])) {
                $mimes[$mime] = 1;
            } else {
                $mimes[$mime]++;
            }

            $category = $guesser->categorize($path, $mime);
            if ($category === null) {
                continue;
            }

            if (!isset($categories[$category])) {
                $categories[$category] = 1;
            } else {
                $categories[$category]++;
            }
        }

        if (count($categories) > 0) {
            arsort($categories);
            $category = array_keys($categories)[0];
        } else {
            $category = null;
        }

        if (count($mimes) > 0) {
            arsort($mimes);
            $mime = array_keys($mimes)[0];
        } else {
            $mime = null;
        }

        return [$category, $mime];
    }
}