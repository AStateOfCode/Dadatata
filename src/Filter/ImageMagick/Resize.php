<?php

namespace Asoc\Dadatata\Filter\ImageMagick;

use Asoc\Dadatata\Exception\ProcessingFailedException;
use Asoc\Dadatata\Filter\BaseMagickFilter;
use Asoc\Dadatata\Filter\ImageOptions;
use Asoc\Dadatata\Filter\OptionsInterface;
use Asoc\Dadatata\Model\ImageInterface;
use Asoc\Dadatata\Model\ThingInterface;

class Resize extends BaseMagickFilter
{
    /**
     * @param ThingInterface|ImageInterface $thing
     * @param string                        $sourcePath
     * @param OptionsInterface|ImageOptions $options
     *
     * @throws \Asoc\Dadatata\Exception\ProcessingFailedException
     * @return array Paths to generated files
     */
    public function process(ThingInterface $thing, $sourcePath, OptionsInterface $options = null)
    {
        $tmpPath = tempnam(sys_get_temp_dir(), 'Dadatata');

        $options = $this->defaults->merge($options);

        $pb = $this->getConvertProcess();
        $pb->add('-quality')->add($options->getQuality());

        $width  = $options->getWidth();
        $height = $options->getHeight();

        list($resizeRatio, $width, $height) = $this->getProperSize(
            $width,
            $height,
            $thing->getWidth(),
            $thing->getHeight()
        );

        // only perform a resize when width and/or height changed
        if ($thing->getWidth() !== $width || $thing->getHeight() !== $height) {
            $pb->add('-resize')->add(sprintf('%dx%d', $width, $height));
        }

        $pb->add(sprintf('%s[0]', $sourcePath));
        $pb->add(sprintf('%s:%s', $options->getFormat(), $tmpPath));

        $process = $pb->getProcess();
        $code    = $process->run();

        if ($code !== 0) {
            throw ProcessingFailedException::create(
                'Failed to resize image',
                $code,
                $process->getOutput(),
                $process->getErrorOutput()
            );
        }

        return [$tmpPath];
    }
}