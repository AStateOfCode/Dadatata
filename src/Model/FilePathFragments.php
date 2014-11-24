<?php

namespace Asoc\Dadatata\Model;

class FilePathFragments
{
    /**
     * @var \SplFileInfo[]
     */
    private $fileInfos;

    public function __construct(array $paths)
    {
        $this->fileInfos = [];

        foreach ($paths as $path) {
            $this->fileInfos[] = new \SplFileInfo($path);
        }
    }

    /**
     * @return \SplFileInfo[]
     */
    public function getFileInfos()
    {
        return $this->fileInfos;
    }

    public function getNum()
    {
        return count($this->fileInfos);
    }
}