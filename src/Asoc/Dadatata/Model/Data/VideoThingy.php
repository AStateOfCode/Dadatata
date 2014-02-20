<?php

namespace Asoc\Dadatata\Model\Data;

trait VideoThingy {

    /**
     * @var int
     */
    protected $width;
    /**
     * @var int
     */
    protected $height;
    /**
     * @var string
     */
    protected $format;
    /**
     * @var string
     */
    protected $codec;
    /**
     * @var
     */
    protected $bitrate;
    /**
     * @var string
     */
    protected $bitrateMode;
    /**
     * @var int
     */
    protected $duration;

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
     * @param mixed $bitrate
     */
    public function setBitrate($bitrate)
    {
        $this->bitrate = $bitrate;
    }

    /**
     * @return mixed
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
     * @param int $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @param int $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }

}