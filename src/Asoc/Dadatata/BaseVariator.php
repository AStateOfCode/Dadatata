<?php

namespace Asoc\Dadatata;

use Asoc\Dadatata\Filter\FilterInterface;
use Asoc\Dadatata\Model\FilePathFragments;
use Asoc\Dadatata\Model\ThingInterface;

abstract class BaseVariator implements VariatorInterface
{

    /**
     * @var LibraryInterface
     */
    protected $library;

    protected function __construct(LibraryInterface $library) {
        $this->library = $library;
    }

    /**
     * @param $variant
     * @return FilterInterface
     */
    abstract protected function getFilterForVariant($variant, array &$options = []);

    public function generate(ThingInterface $thing, $variant, array &$options = []) {
        $filter = $this->getFilterForVariant($variant, $options);
        if($filter === null) {
            return null;
        }

        $sourcePath = $this->library->getPath($thing);
        $targetPaths = $filter->process($thing, $sourcePath, $options);

        if($targetPaths === null) {
            return null;
        }

        $data = new FilePathFragments($targetPaths);
        return $this->library->storeVariant($thing, $variant, $data);
    }

} 