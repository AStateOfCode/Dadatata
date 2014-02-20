<?php


namespace Asoc\Dadatata\Filter\PDFBox;

use Asoc\Dadatata\Filter\FilterInterface;
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
     * @param $sourcePath
     * @param array $options
     * @return array Paths to generated files
     */
    public function process(ThingInterface $thing, $sourcePath, array $options = null)
    {
        $tmpPath = tempnam(sys_get_temp_dir(), 'Dadatata');

        $pb = new ProcessBuilder([$this->bin]);
        $pb->add('ExtractText');
        $pb->add($sourcePath);
        $pb->add($tmpPath);

        $process = $pb->getProcess();
        $code = $process->run();

        $x = $process->getOutput();
        $y = $process->getErrorOutput();

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
}