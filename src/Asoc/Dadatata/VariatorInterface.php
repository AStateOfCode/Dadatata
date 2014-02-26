<?php

namespace Asoc\Dadatata;

use Asoc\Dadatata\Filter\OptionsInterface;
use Asoc\Dadatata\Model\FilePathFragments;
use Asoc\Dadatata\Model\ThingInterface;

interface VariatorInterface
{
    /**
     * @param ThingInterface $thing
     * @param string $variant
     * @param string $sourcePath
     * @param OptionsInterface $options
     * @return null|FilePathFragments
     */
    public function generate(ThingInterface $thing, $variant, $sourcePath, OptionsInterface $options = null);

    /**
     * @return array
     */
    public function getSupportedVariants();

    /**
     * @param string $variant Variant name
     * @return bool
     */
    public function hasSupportFor($variant);
}