<?php

namespace Asoc\Dadatata\Filter\Document;

use Asoc\Dadatata\Filter\FilterInterface;
use Asoc\Dadatata\Model\ImageInterface;
use Asoc\Dadatata\Model\ThingInterface;
use Symfony\Component\Process\ProcessBuilder;

class UnoconvConvertFilter implements FilterInterface
{
    /**
     * @var string
     */
    private $bin;
    /**
     * @var string
     */
    private $format;

    public function __construct($bin = '/usr/bin/unoconv')
    {
        $this->bin    = $bin;
        $this->format = 'pdf';
    }

    /**
     * @param string $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    public function canHandle(ThingInterface $thing)
    {
        return $thing instanceof ImageInterface;
    }

    /**
     * @param ThingInterface|ImageInterface $thing
     * @param string                        $sourcePath
     *
     * @return string
     */
    public function process(ThingInterface $thing, $sourcePath)
    {
        $tmpPath = tempnam(sys_get_temp_dir(), 'Dadatata');

        $pb = new ProcessBuilder([$this->bin]);
        $pb->add('--format')->add($this->format);
        $pb->add('--output')->add($tmpPath);
        $pb->add($sourcePath);

        $process = $pb->getProcess();
        $code    = $process->run();

        $x = $process->getOutput();
        $y = $process->getErrorOutput();

        return [$tmpPath];
    }
}