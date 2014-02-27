<?php

namespace Asoc\Dadatata;

use Asoc\Dadatata\Exception\FilterDoesNotSupportInput;
use Asoc\Dadatata\Filter\FilterInterface;
use Asoc\Dadatata\Filter\OptionsInterface;
use Asoc\Dadatata\Model\FilePathFragments;
use Asoc\Dadatata\Model\ThingInterface;

abstract class BaseVariator implements VariatorInterface
{
    /**
     * @param $variant
     * @param array $options
     * @return FilterInterface
     */
    abstract protected function getFilterForVariant($variant, OptionsInterface $options = null);

    public function generate(ThingInterface $thing, $variant, $sourcePath, OptionsInterface $options = null) {
        $filter = $this->getFilterForVariant($variant, $options);

        if(!$filter->canHandle($thing)) {
            throw new FilterDoesNotSupportInput(sprintf('%s does not support %s', get_class($filter), get_class($thing)));
        }

        $targetPaths = $filter->process($thing, $sourcePath, $options);
        if($targetPaths === null) {
            return null;
        }

        return new FilePathFragments($targetPaths);
    }

} 