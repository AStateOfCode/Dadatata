<?php

namespace Asoc\Dadatata\Filter\GraphicsMagick;

use Asoc\Dadatata\Model\DocumentInterface;
use Asoc\Dadatata\Model\ThingInterface;

class PdfRender extends Resize {

    protected $pages;

    protected $density;

    protected $background;

    protected $removeAlpha;

    protected function init()
    {
        parent::init();
        $this->removeAlpha = true;
        $this->background = 'white';
        $this->density = 150;
        $this->pages = 'all';
    }

    /**
     * @param mixed $removeAlpha
     */
    public function setRemoveAlpha($removeAlpha)
    {
        $this->removeAlpha = $removeAlpha;
    }

    /**
     * @param mixed $pages
     */
    public function setPages($pages)
    {
        $this->pages = $pages;
    }

    /**
     * @param mixed $density
     */
    public function setDensity($density)
    {
        $this->density = $density;
    }

    /**
     * @param mixed $background
     */
    public function setBackground($background)
    {
        $this->background = $background;
    }

    public function canHandle(ThingInterface $thing)
    {
        return $thing->getMime() === 'application/pdf';
    }

    /**
     * @param ThingInterface|DocumentInterface $thing
     * @param $sourcePath
     * @return array
     */
    public function process(ThingInterface $thing, $sourcePath, array $options = null) {
        $tmpPath = tempnam(sys_get_temp_dir(), 'Dadatata');

        $pb = $this->getConvertProcess();
        $pb->add('-quality')->add($this->quality);

        // so the output resolution won't be crap
        $pb->add('-density')->add(150);

        // should fix resulting image in being black
        $pb->add('-background')->add($this->background);

        if($this->removeAlpha) {
            $pb->add('-alpha')->add('remove');
        }

        $width = $this->width;
        $height = $this->height;

        if($options !== null) {
            if(isset($options['width'])) {
                $width = $options['width'];
            }
            if(isset($options['height'])) {
                $height = $options['height'];
            }
        }

        $pb->add('-resize')->add(sprintf('%dx%d', $width, $height));

        if($this->pages === 'all') {
            $pb->add(sprintf('%s', $sourcePath));
        }
        else {
            $pb->add(sprintf('%s[%s]', $sourcePath, $this->pages));
        }

        $pb->add(sprintf('%s:%s', $this->format, $tmpPath));

        $process = $pb->getProcess();

        $code = $process->run();

        $x = $process->getOutput();
        $y = $process->getErrorOutput();

        $tmpPaths = [];
        if($this->pages === 'all') {
            for($i = 0, $n = $thing->getPages(); $i < $n; $i++) {
                $tmpPaths[] = sprintf('%s-%d', $tmpPath, $i);
            }
        }
        else {
            $tmpPaths[] = $tmpPath;
        }

        return $tmpPaths;
    }
}