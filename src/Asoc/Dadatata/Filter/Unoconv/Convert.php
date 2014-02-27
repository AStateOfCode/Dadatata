<?php

namespace Asoc\Dadatata\Filter\Unoconv;

use Asoc\Dadatata\Exception\ProcessingFailedException;
use Asoc\Dadatata\Filter\DocumentOptions;
use Asoc\Dadatata\Filter\FilterInterface;
use Asoc\Dadatata\Filter\OptionsInterface;
use Asoc\Dadatata\Model\DocumentInterface;
use Asoc\Dadatata\Model\ThingInterface;
use Symfony\Component\Process\ProcessBuilder;

class Convert implements FilterInterface {

    /**
     * @var
     */
    private $bin;

    /**
     * @var DocumentOptions
     */
    private $defaults;

    public function __construct($bin) {
        $this->bin = $bin;
    }

    /**
     * @param OptionsInterface $options
     */
    public function setOptions(OptionsInterface $options)
    {
        if(!($options instanceof DocumentOptions)) {
            $options = new DocumentOptions($options->all());
        }
        $this->defaults = $options;
    }

    /**
     * @param ThingInterface $thing
     * @param string $sourcePath
     * @param \Asoc\Dadatata\Filter\OptionsInterface|null|DocumentOptions $options
     * @throws \Asoc\Dadatata\Exception\ProcessingFailedException
     * @return array Paths to generated files
     */
    public function process(ThingInterface $thing, $sourcePath, OptionsInterface $options = null)
    {
        $tmpPath = tempnam(sys_get_temp_dir(), 'Dadatata');

        $options = $this->defaults->merge($options);

        $pb = new ProcessBuilder([
            $this->bin
        ]);
        $pb->add('--format')->add($options->getFormat());
        $pb->add('--output')->add($tmpPath);
        $pb->add($sourcePath);
        $process = $pb->getProcess();

        $code = $process->run();
        if($code !== 0) {
            throw ProcessingFailedException::create('Failed to convert document to PDF', $code, $process->getOutput(), $process->getErrorOutput());
        }

        return [$tmpPath];
    }

    /**
     * @param ThingInterface $thing
     * @return boolean
     */
    public function canHandle(ThingInterface $thing)
    {
        return $thing instanceof DocumentInterface;
    }

}