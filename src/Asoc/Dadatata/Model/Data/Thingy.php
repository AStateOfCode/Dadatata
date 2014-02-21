<?php

namespace Asoc\Dadatata\Model\Data;

trait Thingy {

    /**
     * @var string
     */
    protected $mime;
    /**
     * @var int
     */
    protected $size;
    /**
     * @var array
     */
    protected $hashes;
    /**
     * @var int
     */
    protected $fragments;

    /**
     * @param int $fragments
     */
    public function setFragments($fragments)
    {
        $this->fragments = $fragments;
    }

    /**
     * @return int
     */
    public function getFragments()
    {
        return $this->fragments;
    }

    /**
     * @param string $mime
     */
    public function setMime($mime)
    {
        $this->mime = $mime;
    }

    /**
     * @return string
     */
    public function getMime()
    {
        return $this->mime;
    }

    /**
     * @param int $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @param array $hashes
     */
    public function setHashes(array $hashes)
    {
        $this->hashes = $hashes;
    }

    /**
     * @return array
     */
    public function getHashes()
    {
        return $this->hashes;
    }

    public function setHash($type, $hash) {
        $this->hashes[$type] = $hash;
    }

    public function getHash($type) {
        return $this->hashes[$type];
    }

}