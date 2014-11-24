<?php

namespace Asoc\Dadatata\Tool\PdfBox;

use Asoc\Dadatata\Exception\InvalidCommandParameterException;

class MergerBuilder extends BaseBuilder
{
    /**
     * @var string
     */
    private $targetFile;

    private $sourceFiles;

    public function __construct(array $arguments = [])
    {
        parent::__construct($arguments);

        $this->sourceFiles = [];
    }

    public function addSourceFile($path)
    {
        $this->sourceFiles[] = $path;
    }

    public function addSourceFiles(array $paths)
    {
        $this->sourceFiles = array_merge($this->sourceFiles, $paths);
    }

    public function setTargetFile($path)
    {
        $this->targetFile = $path;
    }

    public function getProcess()
    {
        if (count($this->sourceFiles) === 0) {
            throw new InvalidCommandParameterException(sprintf('No source files given'));
        }

        foreach ($this->sourceFiles as $sourceFile) {
            $this->add($sourceFile);
        }

        if (null === $this->targetFile) {
            throw new InvalidCommandParameterException('No target file specified');
        }

        $this->add($this->targetFile);

        return parent::getProcess();
    }
}