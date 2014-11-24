<?php

namespace Asoc\Dadatata\Filter\ImageMagick;

use Asoc\Dadatata\Exception\ProcessingFailedException;
use Asoc\Dadatata\Filter\BaseMagickFilter;
use Asoc\Dadatata\Filter\DocumentImageOptions;
use Asoc\Dadatata\Filter\OptionsInterface;
use Asoc\Dadatata\Model\ThingInterface;

class PdfRender extends BaseMagickFilter
{
    public function canHandle(ThingInterface $thing)
    {
        return $thing->getMime() === 'application/pdf';
    }

    /**
     * @param ThingInterface                        $thing
     * @param string                                $sourcePath
     * @param OptionsInterface|DocumentImageOptions $options
     *
     * @throws \Asoc\Dadatata\Exception\ProcessingFailedException
     * @return array
     */
    public function process(ThingInterface $thing, $sourcePath, OptionsInterface $options = null)
    {
        $tmpPath = tempnam(sys_get_temp_dir(), 'Dadatata');

        $options = $this->defaults->merge($options);

        $pb = $this->getConvertProcess();
        $pb->add('-quality')->add($options->getQuality());

        // so the output resolution won't be crap
        $pb->add('-density')->add(150);

        // should fix resulting image in being black
        $pb->add('-background')->add('white');
        $pb->add('-alpha')->add('remove');

        $width  = $options->getWidth();
        $height = $options->getHeight();
        $pb->add('-resize')->add(sprintf('%dx%d', $width, $height));

        $pages = $options->getPages();
        if ($pages === 'all') {
            $pb->add(sprintf('%s', $sourcePath));
        } else {
            $pages = intval($pages);
            $pb->add(sprintf('%s[%s]', $sourcePath, $pages));
        }

        $pb->add(sprintf('%s:%s', $options->getFormat(), $tmpPath));

        $process = $pb->getProcess();
        $code    = $process->run();

        if ($code !== 0) {
            throw ProcessingFailedException::create(
                'Failed to render PDF as image',
                $code,
                $process->getOutput(),
                $process->getErrorOutput()
            );
        }

        $tmpPaths = [];
        if ($pages === 'all') {
            for ($i = 0, $n = $pages; $i < $n; $i++) {
                $tmpPaths[] = sprintf('%s-%d', $tmpPath, $i);
            }
        } else {
            $tmpPaths[] = $tmpPath;
        }

        return $tmpPaths;
    }
}