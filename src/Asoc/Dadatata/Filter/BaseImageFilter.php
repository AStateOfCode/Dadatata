<?php

namespace Asoc\Dadatata\Filter;

abstract class BaseImageFilter implements FilterInterface {

    /**
     * @var ImageOptions
     */
    protected $defaults;

    public function setOptions(OptionsInterface $options)
    {
        if(!($options instanceof ImageOptions)) {
            $options = new ImageOptions($options->all());
        }
        $this->defaults = $options;
    }

    protected function getProperSize($targetWidth, $targetHeight, $sourceWidth, $sourceHeight, $upScale = false) {
        // http://stackoverflow.com/questions/7863653/algorithm-to-resize-image-and-maintain-aspect-ratio-to-fit-iphone
        if($targetWidth > $targetHeight) {
            $shortSideMax = $targetHeight;
            $longSideMax = $targetWidth;
        }
        else {
            $shortSideMax = $targetWidth;
            $longSideMax = $targetHeight;
        }

        if ($sourceWidth >= $sourceHeight)
        {
            $wRatio = $longSideMax / $sourceWidth;
            $hRatio = $shortSideMax / $sourceHeight;
        }
        else
        {
            $wRatio = $shortSideMax / $sourceWidth;
            $hRatio = $longSideMax / $sourceHeight;
        }

        $resizeRatio = min($wRatio, $hRatio);

        if($resizeRatio < 1 || $upScale) {
            $width = $sourceWidth * $resizeRatio;
            $height = $sourceHeight * $resizeRatio;

            return [$resizeRatio, $width, $height];
        }

        return [$resizeRatio, $sourceWidth, $sourceHeight];
    }

} 