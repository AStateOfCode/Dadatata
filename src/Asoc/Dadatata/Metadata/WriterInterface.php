<?php

namespace Asoc\Dadatata\Metadata;

interface WriterInterface {

    public function canHandle($object);

    public function apply($object, array $knowledge);

}