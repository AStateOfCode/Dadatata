<?php

namespace Asoc\Dadatata\Tool;

use Symfony\Component\Process\ProcessBuilder;

class UnoconvBuilder extends ProcessBuilder
{
    private $inputFiles;

    public function supportedFormats()
    {
        $this->add('--show');

        return $this;
    }

    public function format($format)
    {
        $this->add('--format')->add($format);

        return $this;
    }

    public function output($path)
    {
        $this->add('--output')->add($path);

        return $this;
    }

    public function version()
    {
        $this->add('--version');

        return $this;
    }

    public function noLaunch()
    {
        $this->add('--no-launch');

        return $this;
    }

    public function input($files)
    {
        $this->inputFiles = $files;

        return $this;
    }

    public function getProcess()
    {
        if (is_array($this->inputFiles)) {
            foreach ($this->inputFiles as $file) {
                $this->add($file);
            }
        } else {
            $this->add($this->inputFiles);
        }

        return parent::getProcess();
    }
}