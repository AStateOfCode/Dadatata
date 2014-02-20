<?php

namespace Asoc\Dadatata;

use Asoc\Dadatata\Filter\FilterInterface;

class SimpleVariator extends BaseVariator
{
    /**
     * @var FilterInterface[]
     */
    protected $filters;

    public function __construct(LibraryInterface $library, array $filters) {
        parent::__construct($library);
        $this->filters = $filters;
    }

    protected function getFilterForVariant($variant, array &$options = [])
    {
        if(!isset($this->filters[$variant])) {
            throw new \Exception(sprintf('Filter for variant does not exist: %s', $variant));
        }

        return $this->filters[$variant];
    }
}