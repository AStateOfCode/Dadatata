<?php

namespace Asoc\Dadatata\Tool\PdfBox;

class ExtractTextBuilder extends BaseBuilder
{
    private $outputFile;
    private $sourceFile;

    public function encoding($encoding)
    {
        $this->add('-encoding')->add($encoding);

        return $this;
    }

    public function password($password)
    {
        $this->add('-password')->add($password);

        return $this;
    }

    public function force()
    {
        $this->add('-force');

        return $this;
    }

    public function source($file)
    {
        $this->sourceFile = $file;

        return $this;
    }

    public function output($file)
    {
        $this->outputFile = $file;

        return $this;
    }

    public function getProcess()
    {
        if (null !== $this->sourceFile) {
            $this->add($this->sourceFile);
        }
        if (null !== $this->outputFile) {
            $this->add($this->outputFile);
        }

        return parent::getProcess();
    }
}