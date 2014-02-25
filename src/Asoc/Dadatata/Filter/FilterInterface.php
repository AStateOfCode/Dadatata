<?php

namespace Asoc\Dadatata\Filter;


use Asoc\Dadatata\Model\ThingInterface;

interface FilterInterface {

    /**
     * @param array $options
     */
    public function setOptions(array $options);

    /**
     * @param ThingInterface $thing
     * @param $sourcePath
     * @param array $options
     * @return array Paths to generated files
     */
    public function process(ThingInterface $thing, $sourcePath, array $options = null);

    /**
     * @param ThingInterface $thing
     * @return boolean
     */
    public function canHandle(ThingInterface $thing);

} 