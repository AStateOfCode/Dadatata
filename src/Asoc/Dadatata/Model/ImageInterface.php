<?php

namespace Asoc\Dadatata\Model;

interface ImageInterface {

    public function getWidth();
    public function setWidth($width);

    public function getHeight();
    public function setHeight($height);

    public function getFormat();
    public function setFormat($format);

}