<?php

namespace Asoc\Dadatata\Filter\ImageMagick;

use Asoc\Dadatata\Model\ThingInterface;

class Thumbnail extends Resize {
    protected function init() {
        parent::init();
        $this->setMode(self::MODE_THUMBNAIL);
    }
}