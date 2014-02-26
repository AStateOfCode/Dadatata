<?php

namespace Asoc\Dadatata\Filter\Php;

use Asoc\Dadatata\Filter\OptionsInterface;
use Imagine\Image\Box;

class ImagineThumbnail extends ImagineResize {

    protected function getTransformation(\Imagine\Image\ImageInterface $image, Box $size, OptionsInterface $options = null) {
        $transformation =  new \Imagine\Filter\Transformation();
        $transformation->thumbnail($size, \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
        return $transformation;
    }

}