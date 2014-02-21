<?php

namespace Asoc\Dadatata\Model;

interface ModelManagerInterface {

    public function save(ThingInterface $thing);

    public function remove(ThingInterface $thing);

}