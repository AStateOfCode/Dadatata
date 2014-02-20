<?php

namespace Asoc\Dadatata\Model;

interface VideoInterface {

    public function getWidth();
    public function setWidth($width);

    public function getHeight();
    public function setHeight($height);

    public function getFormat();
    public function setFormat($format);

    public function getCodec();
    public function setCodec($codec);

    public function getBitrate();
    public function setBitrate($bitrate);

    public function getBitrateMode();
    public function setBitrateMode($mode);

    public function getDuration();
    public function setDuration($duration);

}