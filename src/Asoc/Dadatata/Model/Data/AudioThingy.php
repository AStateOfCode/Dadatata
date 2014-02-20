<?php

namespace Asoc\Dadatata\Model\Data;

trait AudioThingy {

    /**
     * @var int
     */
    protected $bitrate;
    /**
     * @var string
     */
    protected $bitrateMode;
    /**
     * @var string
     */
    protected $format;
    /**
     * @var int
     */
    protected $duration;
    /**
     * @var string
     */
    protected $codec;
    /**
     * @var int
     */
    protected $samplingRate;
    /**
     * @var int
     */
    protected $channels;
    /**
     * @var string
     */
    protected $artist;
    /**
     * @var string
     */
    protected $title;
    /**
     * @var string
     */
    protected $album;

    /**
     * @param int $bitrate
     */
    public function setBitrate($bitrate)
    {
        $this->bitrate = $bitrate;
    }

    /**
     * @return int
     */
    public function getBitrate()
    {
        return $this->bitrate;
    }

    /**
     * @param string $bitrateMode
     */
    public function setBitrateMode($bitrateMode)
    {
        $this->bitrateMode = $bitrateMode;
    }

    /**
     * @return string
     */
    public function getBitrateMode()
    {
        return $this->bitrateMode;
    }

    /**
     * @param int $channels
     */
    public function setChannels($channels)
    {
        $this->channels = $channels;
    }

    /**
     * @return int
     */
    public function getChannels()
    {
        return $this->channels;
    }

    /**
     * @param string $codec
     */
    public function setCodec($codec)
    {
        $this->codec = $codec;
    }

    /**
     * @return string
     */
    public function getCodec()
    {
        return $this->codec;
    }

    /**
     * @param int $duration
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;
    }

    /**
     * @return int
     */
    public function getDuration()
    {
        return $this->duration;
    }

    /**
     * @param string $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @param int $samplingRate
     */
    public function setSamplingRate($samplingRate)
    {
        $this->samplingRate = $samplingRate;
    }

    /**
     * @return int
     */
    public function getSamplingRate()
    {
        return $this->samplingRate;
    }

    /**
     * @param string $album
     */
    public function setAlbum($album)
    {
        $this->album = $album;
    }

    /**
     * @return string
     */
    public function getAlbum()
    {
        return $this->album;
    }

    /**
     * @param string $artist
     */
    public function setArtist($artist)
    {
        $this->artist = $artist;
    }

    /**
     * @return string
     */
    public function getArtist()
    {
        return $this->artist;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

}