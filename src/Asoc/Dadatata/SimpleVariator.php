<?php

namespace Asoc\Dadatata;

use Asoc\Dadatata\Exception\NoFilterDefinedForVariant;
use Asoc\Dadatata\Filter\FilterInterface;
use Asoc\Dadatata\Filter\OptionsInterface;

class SimpleVariator extends BaseVariator
{
    /**
     * @var FilterInterface[]
     */
    protected $filters;

    public function __construct(array $filters) {
        $this->filters = $filters;
    }

    protected function getFilterForVariant($variant, OptionsInterface $options = null)
    {
        if(!isset($this->filters[$variant])) {
            throw new NoFilterDefinedForVariant();
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