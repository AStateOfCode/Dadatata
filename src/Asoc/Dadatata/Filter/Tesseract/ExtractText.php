<?php

namespace Asoc\Dadatata\Filter\Tesseract;

use Asoc\Dadatata\Exception\ProcessingFailedException;
use Asoc\Dadatata\Filter\FilterInterface;
use Asoc\Dadatata\Filter\OptionsInterface;
use Asoc\Dadatata\Model\ImageInterface;
use Asoc\Dadatata\Model\ThingInterface;
use Symfony\Component\Process\ProcessBuilder;

class ExtractText implements FilterInterface {

    /**
     * @var string
     */
    private $bin;
    /**
     * @var OcrOptions
     */
    private $defaults;

    public function __construct($bin = '/usr/bin/tesseract') {
        $this->bin = $bin;
    }

    /**
     * @param ThingInterface $thing
     * @param string $sourcePath
     * @param OptionsInterface $options
     * @throws \Asoc\Dadatata\Exception\ProcessingFailedException
     * @return array Paths to generated files
     */
    public function process(ThingInterface $thing, $sourcePath, OptionsInterface $options = null)
    {
        $tmpPath = tempnam(sys_get_temp_dir(), 'Dadatata');

        /** @var OcrOptions $options */
        $options = $this->defaults->merge($options);

        $pb = new ProcessBuilder([$this->bin]);
        $pb->add('-l')->add($options->getLanguage());
        $pb->add($sourcePath);
        $pb->add($tmpPath);

        $process = $pb->getProcess();

        $code = $process->run();
        if($code !== 0) {
            throw ProcessingFailedException::create('Failed to convert image to text', $code, $process->getOutput(), $process->getErrorOutput());
        }

        return [$tmpPath];
    }

    /**
     * @param ThingInterface $thing
     * @return boolean
     */
    public function canHandle(ThingInterface $thing)
    {
        return $thing instanceof ImageInterface;
    }

    /**
     * @param OptionsInterface $options
     */
    public function setOptions(OptionsInterface $options)
    {
        if(!($options instanceof OcrOptions)) {
            $options = new OcrOptions($options->all());
        }
        $this->defaults = $options;
    }

} 