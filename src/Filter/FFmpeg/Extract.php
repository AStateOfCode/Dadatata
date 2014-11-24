<?php

namespace Asoc\Dadatata\Filter\FFmpeg;

use Asoc\Dadatata\Filter\FilterInterface;
use Asoc\Dadatata\Model\ThingInterface;
use Asoc\Dadatata\Model\VideoInterface;
use Symfony\Component\Process\ProcessBuilder;

class Extract implements FilterInterface
{
    /**
     * @var string
     */
    private $bin;

    public function __construct($bin = '/usr/bin/ffmpeg')
    {
        $this->bin = $bin;
    }

    /**
     * @param ThingInterface $thing
     * @param                $sourcePath
     *
     * @return array Paths to generated files
     */
    public function process(ThingInterface $thing, $sourcePath, array $options = null)
    {
        $tmpPath = tempnam(sys_get_temp_dir(), 'Dadatata');

        $pb = new ProcessBuilder([$this->bin]);
        $pb->add('-y');
        $pb->add('-i')->add($sourcePath);
        $pb->add('-f')->add('mjpeg');
        $pb->add('-vf')->add('thumbnail');
        $pb->add('-frames:v')->add(1);
        $pb->add($tmpPath);

        $process = $pb->getProcess();
        $code    = $process->run();

        $x = $process->getOutput();
        $y = $process->getErrorOutput();

        return [$tmpPath];
    }

    /**
     * @param ThingInterface $thing
     *
     * @return boolean
     */
    public function canHandle(ThingInterface $thing)
    {
        return $thing instanceof VideoInterface;
    }
}