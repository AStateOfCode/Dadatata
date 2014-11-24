<?php

namespace Asoc\Dadatata\Filter\Zbar;

use Asoc\Dadatata\Exception\ProcessingFailedException;
use Asoc\Dadatata\Filter\FilterInterface;
use Asoc\Dadatata\Filter\OptionsInterface;
use Asoc\Dadatata\Model\ImageInterface;
use Asoc\Dadatata\Model\ThingInterface;
use Symfony\Component\Process\ProcessBuilder;

class Extract implements FilterInterface
{
    /**
     * @var string
     */
    private $bin;

    public function __construct($bin = '/usr/bin/zbarimg')
    {
        $this->bin = $bin;
    }

    /**
     * @param OptionsInterface $options
     */
    public function setOptions(OptionsInterface $options)
    {
    }

    /**
     * @param ThingInterface                              $thing
     * @param string                                      $sourcePath
     * @param \Asoc\Dadatata\Filter\OptionsInterface|null $options
     *
     * @throws \Asoc\Dadatata\Exception\ProcessingFailedException
     * @return array Paths to generated files
     */
    public function process(ThingInterface $thing, $sourcePath, OptionsInterface $options = null)
    {
        $tmpPath = tempnam(sys_get_temp_dir(), 'Dadatata');

        $pb = new ProcessBuilder(
            [
                $this->bin
            ]
        );
        $pb->add('--xml');
        $pb->add($sourcePath);
        $process = $pb->getProcess();

        $code = $process->run();
        if ($code !== 0) {
            throw ProcessingFailedException::create(
                'Failed to extract barcodes',
                $code,
                $process->getOutput(),
                $process->getErrorOutput()
            );
        }

        file_put_contents($tmpPath, $process->getOutput());

        return [$tmpPath];
    }

    /**
     * @param ThingInterface $thing
     *
     * @return boolean
     */
    public function canHandle(ThingInterface $thing)
    {
        return $thing instanceof ImageInterface;
    }
}