<?php

namespace Asoc\Dadatata\Filter\LibreOffice;

use Asoc\Dadatata\Exception\ProcessingFailedException;
use Asoc\Dadatata\Filter\DocumentOptions;
use Asoc\Dadatata\Filter\FilterInterface;
use Asoc\Dadatata\Filter\OptionsInterface;
use Asoc\Dadatata\Model\DocumentInterface;
use Asoc\Dadatata\Model\ImageInterface;
use Asoc\Dadatata\Model\TextInterface;
use Asoc\Dadatata\Model\ThingInterface;
use Asoc\Dadatata\Tool\Soffice;
use Asoc\Dadatata\ToolInterface;
use Neutron\TemporaryFilesystem\TemporaryFilesystemInterface;

/**
 * Class Convert
 *
 * @package Asoc\Dadatata\Filter\LibreOffice
 */
class Convert implements FilterInterface
{
    /**
     * @var \Asoc\Dadatata\ToolInterface|Soffice
     */
    private $soffice;
    /**
     * @var \Neutron\TemporaryFilesystem\TemporaryFilesystemInterface
     */
    private $tmpFs;
    /**
     * @var DocumentOptions
     */
    private $defaults;

    public function __construct(ToolInterface $soffice, TemporaryFilesystemInterface $tmpFs)
    {
        $this->soffice = $soffice;
        $this->tmpFs   = $tmpFs;
    }

    /**
     * @param OptionsInterface $options
     */
    public function setOptions(OptionsInterface $options)
    {
        if (!($options instanceof DocumentOptions)) {
            $options = new DocumentOptions($options->all());
        }
        $this->defaults = $options;
    }

    /**
     * @param ThingInterface                        $thing
     * @param string                                $sourcePath
     * @param OptionsInterface|null|DocumentOptions $options
     *
     * @throws \Asoc\Dadatata\Exception\ProcessingFailedException
     * @return array Paths to generated files
     */
    public function process(ThingInterface $thing, $sourcePath, OptionsInterface $options = null)
    {
        $tmpDir = $this->tmpFs->createTemporaryDirectory();

        /** @var DocumentOptions $options */
        $options = $this->defaults->merge($options);

        $pb = $this->soffice
            ->getProcessBuilder()
            ->format($options->getFormat())
            ->input($sourcePath)
            ->outputDir($tmpDir);

        // we override the home directory so it does not cause collisions if soffice is run multiple times concurrently
        $pb->setEnv('HOME', $this->tmpFs->createTemporaryDirectory());

        $process = $pb->getProcess();

        $code = $process->run();

        // https://github.com/dagwieers/unoconv/issues/192
        if($code === 81) {
            $code = $process->run();
        }

        if ($code !== 0) {
            throw ProcessingFailedException::create(
                'Failed to convert document to PDF',
                $code,
                $process->getOutput(),
                $process->getErrorOutput()
            );
        }

        return glob(sprintf('%s/*', $tmpDir));
    }

    /**
     * @param ThingInterface $thing
     *
     * @return boolean
     */
    public function canHandle(ThingInterface $thing)
    {
        return $thing instanceof DocumentInterface
        || $thing instanceof ImageInterface
        || $thing instanceof TextInterface
        || $thing->getMime() === 'text/rtf'
        || $thing->getMime() === 'application/rtf';
    }
}
