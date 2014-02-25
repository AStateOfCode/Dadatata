<?php

namespace Asoc\Dadatata;

use Asoc\Dadatata\Model\ThingInterface;

class AggregateVariator implements VariatorInterface {

    /**
     * @var VariatorInterface[]
     */
    private $variators;

    public function __construct(array $variators) {
        $this->variators = $variators;
    }

    public function generate(ThingInterface $thing, $variant, $sourcePath, array &$options = [])
    {
        foreach($this->variators as $variator) {
            if(!$variator->hasSupportFor($variant)) {
                continue;
            }

            $result = $variator->generate($thing, $variant, $sourcePath, $options);
            if($result !== null) {
                return $result;
            }
        }

        return null;
    }

    /**
     * @return array
     */
    public function getSupportedVariants()
    {
        $supported = [];
        foreach($this->variators as $variator) {
            $supported = array_merge($supported, $variator->getSupportedVariants());
        }
        return $supported;
    }

    /**
     * @param string $variant Variant name
     * @return bool
     */
    public function hasSupportFor($variant)
    {
        foreach($this->variators as $variator) {
            if($variator->hasSupportFor($variant)) {
                return true;
            }
        }
        return false;
    }
}