<?php

namespace Asoc\Dadatata\Filter;

use Asoc\Dadatata\Model\ThingInterface;

class AggregateFilter implements FilterInterface
{
    /**
     * @var FilterInterface[]
     */
    private $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    /**
     * @param ThingInterface   $thing
     * @param string           $sourcePath
     * @param OptionsInterface $options
     *
     * @return array Paths to generated files
     */
    public function process(ThingInterface $thing, $sourcePath, OptionsInterface $options = null)
    {
        foreach ($this->filters as $filter) {
            if ($filter->canHandle($thing)) {
                return $filter->process($thing, $sourcePath, $options);
            }
        }

        return null;
    }

    /**
     * @param ThingInterface $thing
     *
     * @return boolean
     */
    public function canHandle(ThingInterface $thing)
    {
        foreach ($this->filters as $filter) {
            if ($filter->canHandle($thing)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param OptionsInterface $options
     */
    public function setOptions(OptionsInterface $options)
    {
        foreach ($this->filters as $filter) {
            $filter->setOptions($options);
        }
    }
}