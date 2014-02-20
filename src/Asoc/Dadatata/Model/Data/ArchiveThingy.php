<?php

namespace Asoc\Dadatata\Model\Data;

trait ArchiveThingy {

    protected $fileNum;

    protected $format;

    /**
     * @param mixed $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * @return mixed
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param mixed $num
     */
    public function setFileNum($num)
    {
        $this->fileNum = $num;
    }

    /**
     * @return mixed
     */
    public function getFileNum()
    {
        return $this->fileNum;
    }

}