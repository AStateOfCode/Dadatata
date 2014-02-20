<?php

namespace Asoc\Dadatata\Filesystem;

use Asoc\Dadatata\Model\ThingInterface;

class HierarchicalLocator implements LocatorInterface {

    protected $baseDirectory;
    /**
     * @var int
     */
    private $depth;
    /**
     * @var int
     */
    private $characters;

    public function __construct($baseDirectory, $depth = 3, $characters = 2) {
        if($baseDirectory[strlen($baseDirectory)-1] === '/') {
            $baseDirectory = rtrim($baseDirectory, '/');
        }

        $this->baseDirectory = $baseDirectory;
        $this->depth = $depth;
        $this->characters = $characters;
    }

    public function getBaseDirectory() {
        return $this->baseDirectory;
    }

    public function getFilePath(ThingInterface $thing, $fragment = 1)
    {
        return sprintf('%s/%s', $this->baseDirectory, $this->getRelativeFilePath($thing, $fragment));
    }

    public function getRelativeFilePath(ThingInterface $thing, $fragment = 1) {
        return sprintf('%s/%s_%d', $this->getRelativeDirectory($thing), $thing->getKey(), $fragment);
    }

    public function getDirectory(ThingInterface $thing, $fragment = 1) {
        return sprintf('%s/%s', $this->baseDirectory, $this->getRelativeDirectory($thing));
    }

    public function getRelativeDirectory(ThingInterface $thing, $fragment = 1) {
        $key = $thing->getKey();

        if(strlen($key) < $this->depth * $this->characters) {
            throw new \LogicException('Key length is too short for given nesting options');
        }

        $parts = [];
        for($i = 0; $i < $this->depth; $i++) {
            $parts[] = substr($key, $i * $this->characters, $this->characters);
        }

        return implode('/', $parts);
    }

}