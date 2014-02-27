<?php


namespace Asoc\Dadatata\Filter\PDFBox;

use Asoc\Dadatata\Exception\ProcessingFailedException;
use Asoc\Dadatata\Filter\FilterInterface;
use Asoc\Dadatata\Filter\OptionsInterface;
use Asoc\Dadatata\Model\ThingInterface;
use Symfony\Component\Process\ProcessBuilder;

class ExtractText implements FilterInterface {

    /**
     * @var string
     */
    private $bin;

    public function __construct($bin = '/usr/bin/pdfbox') {
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

        $pb = new ProcessBuilder([$this->bin]);
        $pb->add('ExtractText');
        $pb->add($sourcePath);
        $pb->add($tmpPath);

        $process = $pb->getProcess();

        $code = $process->run();
        if($code !== 0) {
            throw ProcessingFailedException::create('Failed to convert PDF to text', $code, $process->getOutput(), $process->getErrorOutput());
        }

        return [$tmpPath];
    }

    /**
     * @param ThingInterface $thing
     * @return boolean
     */
    public function canHandle(ThingInterface $thing)
    {
        return $thing->getMime() === 'application/pdf';
    }

    /**
     * @param OptionsInterface $options
     */
    public function setOptions(OptionsInterface $options)
    {

    }
}