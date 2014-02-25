<?php

namespace Asoc\Dadatata;

use Asoc\Dadatata\Filter\FilterInterface;

class SimpleVariator extends BaseVariator
{
    /**
     * @var FilterInterface[]
     */
    protected $filters;

    public function __construct(array $filters) {
        $this->filters = $filters;
    }

    protected function getFilterForVariant($variant, array &$options = [])
    {
        if(!isset($this->filters[$variant])) {
            throw new \Exception(sprintf('Filter for variant does not exist: %s', $variant));
        }

        return $this->filters[$variant];
    }

    /**
     * @return array
     */
    public function getSupportedVariants()
    {
        return array_keys($this->filters);
    }

    /**
     * @param string $variant Variant name
     * @return bool
     */
    public function hasSupportFor($variant)
    {
        return isset($this->filters[$variant]);
    }
}