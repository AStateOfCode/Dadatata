<?php

namespace Asoc\Dadatata;

use Asoc\Dadatata\Model\FilePathFragments;
use Asoc\Dadatata\Model\ThingInterface;

interface VariatorInterface
{
    /**
     * @param ThingInterface $thing
     * @param string $variant
     * @param string $sourcePath
     * @param array $options
     * @return null|FilePathFragments
     */
    public function generate(ThingInterface $thing, $variant, $sourcePath, array &$options = []);
}