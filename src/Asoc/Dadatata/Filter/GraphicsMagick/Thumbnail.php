<?php

namespace Asoc\Dadatata\Filter\GraphicsMagick;

class Thumbnail extends Resize {
    protected function init() {
        parent::init();
        $this->setMode(self::MODE_THUMBNAIL);
    }
}