<?php

namespace Asoc\Dadatata\Filter\PDFBox;

use Asoc\Dadatata\Exception\ProcessingFailedException;
use Asoc\Dadatata\Filter\DocumentImageOptions;
use Asoc\Dadatata\Filter\FilterInterface;
use Asoc\Dadatata\Filter\OptionsInterface;
use Asoc\Dadatata\Model\ThingInterface;
use Asoc\Dadatata\Tool\PdfBox;
use Asoc\Dadatata\ToolInterface;
use Neutron\TemporaryFilesystem\TemporaryFilesystemInterface;

class PdfToImage implements FilterInterface
{
    /**
     * @var \Asoc\Dadatata\ToolInterface|PdfBox
     */
    private $pdfBox;
    /**
     * @var \Neutron\TemporaryFilesystem\TemporaryFilesystemInterface
     */
    private $tmpFs;
    /**
     * @var OptionsInterface
     */
    private $defaults;

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
        $tmpDir = $this->tmpFs->createTemporaryDirectory();

        /** @var DocumentImageOptions $options */
        $options = $this->defaults->merge($options);

        $pb = $this->pdfBox->getProcessBuilder()
            ->toImage()
            ->imageType($options->getFormat())
            ->output($tmpDir)
            ->source($sourcePath);

        if ($options->has($options::OPTION_PAGES)) {
            $pages = $options->getPages();

            if (strpos($pages, '-') !== false) {
                list($startPage, $endPage) = explode('-', $pages);
            } else {
                $startPage = $pages;
            }

            $startPage = intval($startPage);
            if (isset($endPage)) {
                $endPage = intval($endPage);
            }

            if ($startPage === 0) {
                // one based
                $startPage = 1;

                if (isset($endPage)) {
                    $endPage++;
                }
            }

            $pb->startPage($startPage);

            if (isset($endPage)) {
                $pb->endPage($endPage);
            }
        }

        $process = $pb->getProcess();

        $code = $process->run();
        if ($code !== 0) {
            throw ProcessingFailedException::create(
                'Failed to convert PDF to image',
                $code,
                $process->getOutput(),
                $process->getErrorOutput()
            );
        }

        $outputFiles = glob($tmpDir.'*.jpg');

        return $outputFiles;
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
        if (!($options instanceof DocumentImageOptions)) {
            $options = new DocumentImageOptions($options->all());
        }
        $this->defaults = $options;
    }
}