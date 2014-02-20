<?php

namespace Asoc\Dadatata;

use Asoc\Dadatata\Model\ThingInterface;

interface LibraryInterface
{
    public function getVariantPath(ThingInterface $thing, $fragment = 1);

    public function storeVariant(ThingInterface $thing, $variant, $data);

    public function fetchVariant(ThingInterface $thing, $variant, $fragment = 1);

    /**
     * @param $data
     * @return ThingInterface
     */
    public function store($data);

    public function remove(ThingInterface $thing);

    /***
     * @param ThingInterface $thing
     * @return string
     */
    public function getPath(ThingInterface $thing, $fragment = 1);

    /**
     * @param ThingInterface $thing
     * @return mixed
     */
    public function fetch(ThingInterface $thing, $fragment = 1);

    public function removeVariant(ThingInterface $thing, $variant);
}