<?php

namespace Asoc\Dadatata\Filesystem;

use Asoc\Dadatata\Model\ThingInterface;

interface LocatorInterface {

    public function getBaseDirectory();

    public function getFilePath(ThingInterface $thing, $fragment = 1);

    public function getRelativeFilePath(ThingInterface $thing, $fragment = 1);

    public function getDirectory(ThingInterface $thing, $fragment = 1);

    public function getRelativeDirectory(ThingInterface $thing, $fragment = 1);

}