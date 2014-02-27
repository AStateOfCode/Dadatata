<?php

namespace Asoc\Dadatata\Filter\ImageMagick;

use Asoc\Dadatata\Exception\ProcessingFailedException;
use Asoc\Dadatata\Filter\BaseMagickFilter;
use Asoc\Dadatata\Filter\ImageOptions;
use Asoc\Dadatata\Filter\OptionsInterface;
use Asoc\Dadatata\Model\ImageInterface;
use Asoc\Dadatata\Model\ThingInterface;

class Thumbnail extends BaseMagickFilter {

    /**
     * @param ThingInterface|ImageInterface $thing
     * @param string $sourcePath
     * @param OptionsInterface|ImageOptions $options
     * @throws \Asoc\Dadatata\Exception\ProcessingFailedException
     * @return array Paths to generated files
     */
    public function process(ThingInterface $thing, $sourcePath, OptionsInterface $options = null)
    {
        $tmpPath = tempnam(sys_get_temp_dir(), 'Dadatata');

        $options = $this->defaults->merge($options);

        $pb = $this->getConvertProcess();
        $pb->add('-quality')->add($options->getQuality());

        $width = $options->getWidth();
        $height = $options->getHeight();

        if($thing->getWidth() > $width && $thing->getHeight() > $height) {
            if($width === $height) {
                // square image
                // http://www.imagemagick.org/Usage/thumbnails/#cut
                $single = sprintf('%dx%d', $width, $height);
                $doubled = sprintf('%dx%d', $width*2, $height*2);
                //$pb->add('-define')->add(sprintf('jpeg:size=%s', $doubled));
                $pb->add('-thumbnail')->add(sprintf('%s^', $single));
                $pb->add('-gravity')->add('center');
                $pb->add('-extent')->add($single);
            }
            else {
                $pb->add('-thumbnail')->add(sprintf('%dx%d', $width, $height));
            }
        }

        $pb->add(sprintf('%s[0]', $sourcePath));
        $pb->add(sprintf('%s:%s', $options->getFormat(), $tmpPath));

        $process = $pb->getProcess();
        $code = $process->run();

        if($code !== 0) {
            throw ProcessingFailedException::create('Failed to create thumbnail', $code, $process->getOutput(), $process->getErrorOutput());
        }

        return [$tmpPath];
    }
}