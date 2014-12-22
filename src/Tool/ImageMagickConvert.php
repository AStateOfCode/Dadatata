<?php

namespace Asoc\Dadatata\Tool;

use Asoc\Dadatata\Tool\ImageMagick\ConvertBuilder;
use Asoc\Dadatata\ToolInterface;
use Symfony\Component\Process\ExecutableFinder;
use Symfony\Component\Process\ProcessBuilder;

/**
 * Class ImageMagickConvert
 *
 * @package Asoc\Dadatata\Tool
 */
class ImageMagickConvert implements ToolInterface
{
    /**
     * @var string
     */
    private $bin;

    /**
     * @var string
     */
    private $version;

    /**
     * @param string $bin Path to convert executable.
     */
    public function __construct($bin)
    {
        $this->bin = $bin;
    }

    /**
     * @param array $directories Additional directories to search for the executable
     *
     * @return ToolInterface|null
     */
    public static function create($directories = [])
    {
        $finder = new ExecutableFinder();
        $bin    = $finder->find('convert', null, $directories);

        if (null === $bin) {
            return null;
        }

        $tool = new static($bin);

        return $tool;
    }

    /**
     * @return ProcessBuilder|ConvertBuilder
     */
    public function getProcessBuilder()
    {
        $pb = new ConvertBuilder([$this->bin]);

        return $pb;
    }

    /**
     * @return null|false|string null = not available, false = failed to retrieve, string = version
     */
    public function getVersion()
    {
        if (null === $this->version) {
            $process = (new ProcessBuilder([$this->getBin()]))->add('--version')->getProcess();
            $process->run();

            // Version: ImageMagick 6.7.7-10 2014-03-08 Q16 http://www.imagemagick.org
            // Version: ImageMagick 6.9.0-0 Q16 x86_64 2014-11-17 http://www.imagemagick.org
            if (preg_match('/ImageMagick (\d+\.\d+\.\d+-\d+).*(Q\d+)/', $process->getOutput(), $version)) {
                $this->version = sprintf('%s %s', $version[1], $version[2]);
            } else {
                $this->version = false;
            }
        }

        return $this->version;
    }

    /**
     * @return string Path to convert executable.
     */
    public function getBin()
    {
        return $this->bin;
    }
}