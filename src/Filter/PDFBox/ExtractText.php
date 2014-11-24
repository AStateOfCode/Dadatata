<?php

namespace Asoc\Dadatata\Filter\PDFBox;

use Asoc\Dadatata\Exception\ProcessingFailedException;
use Asoc\Dadatata\Filter\FilterInterface;
use Asoc\Dadatata\Filter\OptionsInterface;
use Asoc\Dadatata\Model\ThingInterface;
use Asoc\Dadatata\Tool\PdfBox;
use Asoc\Dadatata\ToolInterface;
use Neutron\TemporaryFilesystem\TemporaryFilesystemInterface;

class ExtractText implements FilterInterface
{
    /**
     * @var \Asoc\Dadatata\ToolInterface|PdfBox
     */
    private $pdfBox;
    /**
     * @var \Neutron\TemporaryFilesystem\TemporaryFilesystemInterface
     */
    private $tmpFs;

    public function __construct(ToolInterface $pdfBox, TemporaryFilesystemInterface $tmpFs)
    {
        $this->pdfBox = $pdfBox;
        $this->tmpFs  = $tmpFs;
    }

    /**
     * @param ThingInterface   $thing
     * @param string           $sourcePath
     * @param OptionsInterface $options
     *
     * @throws \Asoc\Dadatata\Exception\ProcessingFailedException
     * @return array Paths to generated files
     */
    public function process(ThingInterface $thing, $sourcePath, OptionsInterface $options = null)
    {
        $tmpDir  = $this->tmpFs->createTemporaryDirectory();
        $tmpFile = $tmpDir.DIRECTORY_SEPARATOR.$thing->getKey();

        $pb      = $this->pdfBox->getProcessBuilder()
            ->extractText()
            ->source($sourcePath)
            ->output($tmpFile);
        $process = $pb->getProcess();

        $code = $process->run();
        if ($code !== 0) {
            throw ProcessingFailedException::create(
                'Failed to convert PDF to text',
                $code,
                $process->getOutput(),
                $process->getErrorOutput()
            );
        }

        return [$tmpFile];
    }

    /**
     * @param ThingInterface $thing
     *
     * @return boolean
     */
    public function canHandle(ThingInterface $thing)
    {
        return $thing->getMime() === 'application/pdf';
    }

    /**
     * @param OptionsInterface $options
     */
    public function setOptions(OptionsInterface $options)
    {
    }
}