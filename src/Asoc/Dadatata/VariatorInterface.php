<?php

namespace Asoc\Dadatata;

use Asoc\Dadatata\Model\ThingInterface;

interface VariatorInterface
{
    public function generate(ThingInterface $thing, $variant, array &$options = []);
}