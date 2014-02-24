<?php

namespace Asoc\Dadatata;

use Asoc\Dadatata\Model\ThingInterface;

interface LibraryInterface
{
    /**
     * @param mixed $data
     * @param ThingInterface $thing
     * @return ThingInterface
     */
    public function identify($data, ThingInterface $thing = null);

    /**
     * @param ThingInterface $thing
     * @param string $variant
     * @param int $fragment
     * @return string
     */
    public function getVariantPath(ThingInterface $thing, $variant, $fragment = 1);

    /**
     * @param ThingInterface $thing
     * @param $variant
     * @param $data
     * @return ThingInterface
     */
    public function storeVariant(ThingInterface $thing, $variant, $data);

    /**
     * @param ThingInterface $thing
     * @param $variant
     * @param int $fragment
     * @return mixed Contents of the thing variant fragment
     */
    public function fetchVariant(ThingInterface $thing, $variant, $fragment = 1);

    /**
     * @param $data
     * @param Model\ThingInterface $thing
     * @return ThingInterface
     */
    public function store($data, ThingInterface $thing = null);

    /**
     * @param ThingInterface $thing
     */
    public function remove(ThingInterface $thing);

    /**
     * @param ThingInterface $thing
     * @param int $fragment
     * @return string
     */
    public function getPath(ThingInterface $thing, $fragment = 1);

    /**
     * @param ThingInterface $thing
     * @param int $fragment
     * @return mixed Contents of the thing
     */
    public function fetch(ThingInterface $thing, $fragment = 1);

    /**
     * @param ThingInterface $thing
     * @param $variant
     * @return mixed
     */
    public function removeVariant(ThingInterface $thing, $variant);
}