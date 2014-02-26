<?php


namespace Asoc\Dadatata\Filter;

use Asoc\Dadatata\Model\ImageInterface;
use Asoc\Dadatata\Model\ThingInterface;
use Symfony\Component\Process\ProcessBuilder;

abstract class BaseMagickFilter extends BaseImageFilter {

    /**
     * @var string
     */
    protected $bin;

    public function __construct($bin = '/usr/bin/convert') {
        $this->bin = $bin;
    }

    /**
     * @return ProcessBuilder
     */
    protected function getConvertProcess()
    {
        return new ProcessBuilder([$this->bin]);
    }

    public function canHandle(ThingInterface $thing)
    {
        return $thing instanceof ImageInterface;
    }

} 