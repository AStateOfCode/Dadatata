<?php

namespace Asoc\Dadatata\Model;

interface ModelProviderInterface {

    /**
     * @param string|null $category
     * @param string|null $key
     * @return ThingInterface
     */
    public function create($category = null, $key = null);

} 