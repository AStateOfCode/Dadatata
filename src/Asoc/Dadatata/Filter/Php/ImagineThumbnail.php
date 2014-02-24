<?php

namespace Asoc\Dadatata\Filter\Php;

use Imagine\Image\Box;

class ImagineThumbnail extends ImagineResize {

    /**
     * thumbnail or empty
     *
     * @var string
     */
    protected $mode;

    /**
     * @param string $mode
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
    }

    protected function init()
    {
        parent::init();
        $this->mode = \Imagine\Image\ImageInterface::THUMBNAIL_OUTBOUND;
    }

    protected function getTransformation(\Imagine\Image\ImageInterface $image, Box $size, array $options = null) {
        $mode = $this->mode;

        if(isset($options['mode'])) {
            $mode = $options['mode'];
        }

        $transformation =  new \Imagine\Filter\Transformation();
        $transformation->thumbnail($size, $mode);
        return $transformation;
    }

}