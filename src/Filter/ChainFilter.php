<?php

namespace Asoc\Dadatata\Filter;

use Asoc\Dadatata\Model\ThingInterface;

class ChainFilter implements FilterInterface
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
        $result = null;
        foreach ($this->filters as $filter) {
            if ($result === null) {
                $result = $filter->process($thing, $sourcePath, $options);
            } else {
                $result = $filter->process($thing, $result[0], $options);
            }
        }

        return $result;
    }

    /**
     * @param ThingInterface $thing
     *
     * @return boolean
     */
    public function canHandle(ThingInterface $thing)
    {
        return $this->filters[0]->canHandle($thing);
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