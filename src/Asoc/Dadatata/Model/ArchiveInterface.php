<?php

namespace Asoc\Dadatata\Model;

interface ArchiveInterface {

    public function setFormat($format);
    public function getFormat();

    public function setFileNum($num);
    public function getFileNum();

}