<?php

namespace Asoc\Dadatata;

use Asoc\Dadatata\Filter\FilterInterface;
use Asoc\Dadatata\Model\FilePathFragments;
use Asoc\Dadatata\Model\ThingInterface;

abstract class BaseVariator implements VariatorInterface
{
    /**
     * @param $variant
     * @param array $options
     * @return FilterInterface
     */
    abstract protected function getFilterForVariant($variant, array &$options = []);

    public function generate(ThingInterface $thing, $variant, $sourcePath, array &$options = []) {
        $filter = $this->getFilterForVariant($variant, $options);
        if($filter === null) {
            return null;
        }

        $targetPaths = $filter->process($thing, $sourcePath, $options);
        if($targetPaths === null) {
            return null;
        }

        return new FilePathFragments($targetPaths);
    }

} 