<?php

namespace Asoc\Dadatata\Filter\Tesseract;

use Asoc\Dadatata\Exception\ProcessingFailedException;
use Asoc\Dadatata\Filter\FilterInterface;
use Asoc\Dadatata\Filter\OptionsInterface;
use Asoc\Dadatata\Model\ImageInterface;
use Asoc\Dadatata\Model\ThingInterface;
use Asoc\Dadatata\Tool\Tesseract;
use Asoc\Dadatata\ToolInterface;
use Neutron\TemporaryFilesystem\TemporaryFilesystemInterface;

class ExtractText implements FilterInterface {

    /**
     * @var \Asoc\Dadatata\ToolInterface|Tesseract
     */
    private $tesseract;
    /**
     * @var \Neutron\TemporaryFilesystem\TemporaryFilesystemInterface
     */
    private $tmpFs;
    /**
     * @var OcrOptions
     */
    private $defaults;

    public function __construct(ToolInterface $tesseract, TemporaryFilesystemInterface $tmpFs) {
        $this->tesseract = $tesseract;
        $this->tmpFs = $tmpFs;
    }

    /**
     * @param ThingInterface $thing
     * @param string $sourcePath
     * @param OptionsInterface $options
     * @throws \Asoc\Dadatata\Exception\ProcessingFailedException
     * @return array Paths to generated files
     */
    public function process(ThingInterface $thing, $sourcePath, OptionsInterface $options = null)
    {
        $tmpDir = $this->tmpFs->createTemporaryDirectory();
        $tmpFile = $tmpDir.DIRECTORY_SEPARATOR.$thing->getKey();

        /** @var OcrOptions $options */
        $options = $this->defaults->merge($options);

        $pb = $this->tesseract->getProcessBuilder()
            ->language($options->getLanguage())
            ->source($sourcePath)
            ->output($tmpFile);

        $process = $pb->getProcess();

        $code = $process->run();
        if($code !== 0) {
            throw ProcessingFailedException::create('Failed to convert image to text', $code, $process->getOutput(), $process->getErrorOutput());
        }

        return [$tmpFile];
    }

    /**
     * @param ThingInterface $thing
     * @return boolean
     */
    public function canHandle(ThingInterface $thing)
    {
        return $thing instanceof ImageInterface;
    }

    /**
     * @param OptionsInterface $options
     */
    public function setOptions(OptionsInterface $options)
    {
        if(!($options instanceof OcrOptions)) {
            $options = new OcrOptions($options->all());
        }
        $this->defaults = $options;
    }

} 