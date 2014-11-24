<?php

namespace Asoc\Dadatata\Filter\Php;

use Asoc\Dadatata\Filter\OptionsInterface;
use Asoc\Dadatata\Filter\ThumbnailOptions;
use Imagine\Image\Box;

class ImagineThumbnail extends ImagineResize
{
    public function setOptions(OptionsInterface $options)
    {
        if (!($options instanceof ThumbnailOptions)) {
            $options = new ThumbnailOptions($options->all());
        }
        $this->defaults = $options;
    }

    protected function getTransformation(
        \Imagine\Image\ImageInterface $image,
        Box $size,
        OptionsInterface $options = null
    ) {
        $transformation = new \Imagine\Filter\Transformation();

        if ($options->getMode() === ThumbnailOptions::MODE_OUTBOUND) {
            $transformation->thumbnail($size, \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND);
        } else {
            $transformation->thumbnail($size, \Imagine\Image\ImageInterface::THUMBNAIL_INSET);
        }

        return $transformation;
    }
}