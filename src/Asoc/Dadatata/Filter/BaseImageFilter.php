<?php

namespace Asoc\Dadatata\Filter;

abstract class BaseImageFilter implements FilterInterface {

    const FORMAT_JPG = 'jpeg';
    const FORMAT_PNG = 'png';
    const FORMAT_WEBP = 'webp';

    const JPG_QUALITY_BEST = 100;
    const JPG_QUALITY_FINE = 90;
    const JPG_QUALITY_GOOD = 80;
    const JPG_QUALITY_AVERAGE = 50;

    const PNG_QUALITY_BEST = 0;
    const PNG_QUALITY_FINE = 3;
    const PNG_QUALITY_GOOD = 6;
    const PNG_QUALITY_AVERAGE = 9;

    const WEBP_QUALITY_BEST = 100;
    const WEBP_QUALITY_FINE = 90;
    const WEBP_QUALITY_GOOD = 80;
    const WEBP_QUALITY_AVERAGE = 50;

    const MODE_THUMBNAIL = 'thumbnail';

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