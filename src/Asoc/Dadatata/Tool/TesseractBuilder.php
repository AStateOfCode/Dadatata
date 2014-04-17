<?php

namespace Asoc\Dadatata\Tool;

use Symfony\Component\Process\ProcessBuilder;

class TesseractBuilder extends ProcessBuilder {

    private $outputBase;
    private $sourceFile;
    private $lang;

    public function listLanguages() {
        $this->add('--list-langs');
        $this->clearNonSingleOptions();
        return $this;
    }

    public function language($language) {
        $this->lang = $language;
        return $this;
    }

    public function source($file) {
        $this->sourceFile = $file;
        return $this;
    }

    public function output($pathBase) {
        $this->outputBase = $pathBase;
        return $this;
    }

    public function version() {
        $this->add('--version');
        $this->clearNonSingleOptions();
        return $this;
    }

    public function getProcess()
    {
        if(null !== $this->sourceFile) {
            $this->add($this->sourceFile);
        }

        if(null !== $this->outputBase) {
            $this->add($this->outputBase);
        }

        if(null !== $this->lang) {
            $this->add('-l')->add($this->lang);
        }

        return parent::getProcess();
    }

    private function clearNonSingleOptions() {
        $this->outputBase = $this->sourceFile = $this->lang = null;
    }
} 