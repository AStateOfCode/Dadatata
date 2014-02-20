<?php

namespace Asoc\Dadatata\Filter\GraphicsMagick;

use Asoc\Dadatata\Filter\BaseMagickFilter;
use Asoc\Dadatata\Model\ThingInterface;
use Symfony\Component\Process\ProcessBuilder;

class Resize extends BaseMagickFilter {

    public function __construct($bin = '/usr/bin/gm') {
        $this->bin = $bin;
        $this->init();
    }

    /**
     * @return ProcessBuilder
     */
    protected function getConvertProcess()
    {
        return new ProcessBuilder([$this->bin, 'convert']);
    }

    /**
     * @param ThingInterface $thing
     * @param $sourcePath
     * @return array Paths to generated files
     */
    public function process(ThingInterface $thing, $sourcePath, array $options = null)
    {
        return $this->firstToImage($thing, $sourcePath);
    }
}