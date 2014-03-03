<?php

namespace Asoc\Dadatata\Filesystem;

use Asoc\Dadatata\Model\ThingInterface;

/**
 * Builds nested hierarchical paths based on the key property of a file metadata object.
 *
 * {BASEDIRECTORY}/{KEY_PART1/{KEY_PART2}/{KEY}
 * {BASEDIRECTORY}/68/b7/68b7d0b12984dba40d5be5274144e534dfdee4bd21d144e40575e4fb7a7b6dbb_1
 */
class HierarchicalLocator implements LocatorInterface {

    /**
     * Root directory
     *
     * @var string
     */
    protected $baseDirectory;
    /**
     * Num of sub-directories that should be created
     *
     * @var int
     */
    private $depth;
    /**
     * Num of characters each sub-directory name consists of
     *
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