<?php

namespace Asoc\Dadatata\Filter\Jpegoptim;

use Asoc\Dadatata\Exception\ProcessingFailedException;
use Asoc\Dadatata\Filter\FilterInterface;
use Asoc\Dadatata\Filter\OptionsInterface;
use Asoc\Dadatata\Model\ImageInterface;
use Asoc\Dadatata\Model\ThingInterface;
use Symfony\Component\Process\ProcessBuilder;

class Optimize implements FilterInterface
{
    /**
     * @var
     */
    private $bin;
    /**
     * @var OptimizeOptions
     */
    private $defaults;

    public function __construct($bin)
    {
        $this->bin = $bin;
    }

    /**
     * @param OptionsInterface $options
     */
    public function setOptions(OptionsInterface $options)
    {
        if (!($options instanceof OptimizeOptions)) {
            $options = new OptimizeOptions($options->all());
        }
        $this->defaults = $options;
    }

    /**
     * @param ThingInterface                              $thing
     * @param string                                      $sourcePath
     * @param \Asoc\Dadatata\Filter\OptionsInterface|null $options
     *
     * @return array Paths to generated files
     */
    public function process(ThingInterface $thing, $sourcePath, OptionsInterface $options = null)
    {
        // jpegoptim wants a directory
        $tmpPath = sys_get_temp_dir();

        /** @var OptimizeOptions $options */
        $options = $this->defaults->merge($options);

        $pb = new ProcessBuilder([$this->bin]);
        $pb->add('--dest')->add($tmpPath);
        $pb->add('--max')->add($options->getQuality());
        $pb->add('--strip-'.$options->getStrip());

        if ($options->has(OptimizeOptions::OPTION_THRESHOLD)) {
            $pb->add('--threshold')->add($options->getThreshold());
        }

        $pb->add($sourcePath);
        $process = $pb->getProcess();

        $code = $process->run();
        if ($code !== 0) {
            throw ProcessingFailedException::create(
                'Failed to optimize JPEG',
                $code,
                $process->getOutput(),
                $process->getErrorOutput()
            );
        }

        // output directory + name of file
        return [sprintf('%s/%s', $tmpPath, basename($sourcePath))];
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