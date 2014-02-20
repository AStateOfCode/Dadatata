<?php

namespace Asoc\Dadatata\Metadata;

use Asoc\Dadatata\Model\ThingInterface;

interface DescriptorInterface {

    public function describe(ThingInterface $thing, array $knowledge);

}