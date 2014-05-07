<?php

namespace Asoc\Dadatata\Tool\PdfBox;

class ToImageBuilder extends BaseBuilder {

    /**
     * @var
     */
    private $sourceFile;

    public function imageType($type) {
        $this->add('-imageType')->add($type);
        return $this;
    }

    public function startPage($page) {
        $this->add('-startPage')->add($page);
        return $this;
    }

    public function endPage($page) {
        $this->add('-endPage')->add($page);
        return $this;
    }

    public function output($directory) {
        $this->add('-outputPrefix')->add($directory);
        return $this;
    }

    public function source($file) {
        $this->sourceFile = $file;
        return $this;
    }

    public function getProcess()
    {
        if(null !== $this->sourceFile) {
            $this->add($this->sourceFile);
        }

        return parent::getProcess();
    }

} 