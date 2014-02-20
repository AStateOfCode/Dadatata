<?php

namespace Asoc\Dadatata\Model;

interface AudioInterface {

    public function setBitrate($bitrate);
    public function getBitrate();

    public function setBitrateMode($mode);
    public function getBitrateMode();

    public function setDuration($seconds);
    public function getDuration();

    public function setCodec($codec);
    public function getCodec();

    public function setFormat($format);
    public function getFormat();

    public function setSamplingRate($rate);
    public function getSamplingRate();

    public function setChannels($channels);
    public function getChannels();

    public function setArtist($artist);
    public function getArtist();

    public function setTitle($title);
    public function getTitle();

    public function setAlbum($album);
    public function getAlbum();

}