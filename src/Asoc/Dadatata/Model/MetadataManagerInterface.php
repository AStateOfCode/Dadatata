<?php

namespace Asoc\Dadatata\Model;

interface MetadataManagerInterface {

    public function save(ThingInterface $thing);

    public function remove(ThingInterface $thing);

}