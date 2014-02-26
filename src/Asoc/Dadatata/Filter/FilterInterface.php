<?php

namespace Asoc\Dadatata\Filter;


use Asoc\Dadatata\Model\ThingInterface;

interface FilterInterface {

    /**
     * @param OptionsInterface $options
     */
    public function setOptions(OptionsInterface $options);

    /**
     * @param ThingInterface $thing
     * @param string $sourcePath
     * @param \Asoc\Dadatata\Filter\OptionsInterface|null $options
     * @return array Paths to generated files
     */
    public function process(ThingInterface $thing, $sourcePath, OptionsInterface $options = null);

    /**
     * @param ThingInterface $thing
     * @return boolean
     */
    public function canHandle(ThingInterface $thing);

} 