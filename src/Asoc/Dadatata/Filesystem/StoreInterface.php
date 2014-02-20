<?php

namespace Asoc\Dadatata\Filesystem;

use Asoc\Dadatata\Model\ThingInterface;

interface StoreInterface {

    public function save(ThingInterface $thing, $data);
    public function load(ThingInterface $thing, $fragment = 1, $asStream = false);
    public function remove(ThingInterface $thing);
    public function getPath(ThingInterface $thing, $createDirectories = false, $fragment = 1);

}