<?php

namespace Asoc\Dadatata\Filesystem;

use Asoc\Dadatata\Model\ThingInterface;

class FlatLocator implements LocatorInterface {

    /**
     * @var
     */
    private $baseDirectory;

    public function __construct($baseDirectory) {

        $this->baseDirectory = $baseDirectory;
    }

    public function getBaseDirectory()
    {
        return $this->baseDirectory;
    }

    public function getFilePath(ThingInterface $thing, $fragment = 1)
    {
        return sprintf('%s/%s', $this->baseDirectory, $this->getRelativeFilePath($thing, $fragment));
    }

    public function getRelativeFilePath(ThingInterface $thing, $fragment = 1) {
        return sprintf('%s_%d', $thing->getKey(), $fragment);
    }

    public function getDirectory(ThingInterface $thing, $fragment = 1) {
        return $this->baseDirectory;
    }

    public function getRelativeDirectory(ThingInterface $thing, $fragment = 1) {
        return '';
    }
}