<?php

namespace Asoc\Dadatata\Tool;

use Symfony\Component\Process\ProcessBuilder;

/**
 * Class SofficeBuilder
 *
 * @package Asoc\Dadatata\Tool
 */
class SofficeBuilder extends ProcessBuilder
{
    /**
     * @var string
     */
    private $convertFormat;

    /**
     * @var string
     */
    private $outputDir;

    /**
     * @var string
     */
    private $inputPath;

    /**
     * @param string $format
     *
     * @return SofficeBuilder
     */
    public function format($format)
    {
        $this->convertFormat = $format;

        return $this;
    }

    /**
     * @param string $inputPath
     *
     * @return SofficeBuilder
     */
    public function input($inputPath)
    {
        $this->inputPath = $inputPath;

        return $this;
    }

    /**
     * @param string $outputDir
     *
     * @return SofficeBuilder
     */
    public function outputDir($outputDir)
    {
        $this->outputDir = $outputDir;

        return $this;
    }

    public function getProcess()
    {
        $this
            ->add('--convert-to')
            ->add($this->convertFormat)
            ->add($this->inputPath);

        $this->add('--outdir')->add($this->outputDir);

        return parent::getProcess();
    }
}