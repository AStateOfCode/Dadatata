<?php

namespace Asoc\Dadatata\Tool\ImageMagick;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;

/**
 * Class Convert
 *
 * @package Asoc\Dadatata\Tool\ImageMagick
 */
class ConvertBuilder extends ProcessBuilder
{
    /**
     * @var string
     */
    private $sourcePath;

    /**
     * @var string
     */
    private $outputPath;

    /**
     * @var array
     */
    private $outputOptions;

    /**
     * @var string
     */
    private $outputFormat;

    /**
     * @param array $arguments
     */
    public function __construct(array $arguments = [])
    {
        parent::__construct($arguments);

        $this->outputOptions = [];
    }

    /**
     * @param string $path
     *
     * @return $this
     */
    public function output($path)
    {
        $this->outputPath = $path;

        return $this;
    }

    /**
     * @param string $path
     *
     * @return $this
     */
    public function source($path)
    {
        $this->sourcePath = $path;

        return $this;
    }

    /**
     * @param string $format
     *
     * @return $this
     */
    public function format($format)
    {
        $this->outputFormat = $format;

        return $this;
    }

    /**
     * @param int $quality
     *
     * @return $this
     */
    public function quality($quality)
    {
        $this->outputOptions['quality'] = $quality;

        return $this;
    }

    /**
     * @return Process
     */
    public function getProcess()
    {
        if (null !== $this->sourcePath) {
            $this->add($this->sourcePath);
        }

        foreach ($this->outputOptions as $name => $value) {
            $this->add('-'.$name);
            $this->add($value);
        }

        $this->add(sprintf('%s:%s', $this->outputFormat, $this->outputPath));

        return parent::getProcess();
    }
}