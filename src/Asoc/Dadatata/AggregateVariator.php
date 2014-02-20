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

    public function generate(ThingInterface $thing, $variant, array &$options = [])
    {
        foreach($this->variators as $variator) {
            $result = $variator->generate($thing, $variant, $options);
            if($result !== null) {
                return $result;
            }
        }

        return null;
    }
}