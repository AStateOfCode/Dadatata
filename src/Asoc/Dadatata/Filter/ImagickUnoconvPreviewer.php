<?php

namespace Asoc\Dadatata\Media\Previewer;

use Asoc\Dadatata\Filesystem\StoreInterface;
use Asoc\Dadatata\Model\HasThingInterface;
use Asoc\Dadatata\Model\PreviewInterface;
use Asoc\Dadatata\Model\ThingInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Process\ProcessBuilder;

class DocumentImagickUnoconvPreviewer implements PreviewerInterface {

    private static $supported = [
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-documentdocument.presentationml.presentation'
    ];

    private $tempDir;
    /**
     * @var \Asoc\Dadatata\Filesystem\StoreInterface
     */
    private $store;
    /**
     * @var string
     */
    private $binConvert;
    /**
     * @var string
     */
    private $binUnoconv;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;
    /**
     * @var string
     */
    private $imageFormat;
    /**
     * @var int
     */
    private $imageQuality;

    public function __construct(StoreInterface $store, $imageFormat, $imageQuality, $binConvert = '/usr/bin/convert', $binUnoconv = '/usr/bin/unoconv', $tempDir = null, LoggerInterface $logger = null) {
        $this->store = $store;
        $this->imageFormat = $imageFormat;
        $this->imageQuality = $imageQuality;
        $this->binConvert = $binConvert;
        $this->binUnoconv = $binUnoconv;
        $this->logger = $logger;
        $this->tempDir = $tempDir;

        if(!is_dir($this->tempDir)) {
            mkdir($this->tempDir);
        }
    }

    public function canHandle(HasThingInterface $obj, ThingInterface $thing = null)
    {
        if($thing === null) {
            $thing = $obj->getThing();
        }

        return in_array($thing->getMime(), self::$supported);
    }

    // using the sys_temp_dir with tempnam() doesn't work because the output file needs to have the right extension (.pdf)
    public function generate(HasThingInterface $obj, PreviewInterface $preview, ThingInterface $thing = null) {
        if($thing === null) {
            $thing = $obj->getThing();
        }
        $sourcePath = $this->store->getPath($obj, $thing);

        $targetPdfPath = sprintf('%s/%s.pdf', $this->tempDir, $thing->getKey());
        touch($targetPdfPath);

        $arguments = [
            'unoconv',
            '--output',
            $targetPdfPath,
            $sourcePath
        ];
        $pb = new ProcessBuilder($arguments);
        $process = $pb->getProcess();
        $code = $process->run();

        if($this->logger !== null) {
            $this->logger->debug($process->getCommandLine());
            $this->logger->debug(sprintf('%d - %s', $code, $process->getOutput()));
            $this->logger->debug(sprintf('%d - %s', $code, $process->getErrorOutput()));
        }

        if(!file_exists($targetPdfPath) || filesize($targetPdfPath) === 0) {
            return false;
        }

        $width = $preview->getWidth();
        $height = $preview->getHeight();
        $targetPreviewPath = $this->store->getPath($obj, true, $preview);
        $arguments = [
            'convert',
            '-density',
            150,
            '-quality',
            $this->imageQuality,
            '-resize',
            sprintf('%dx%d', $width, $height),
            sprintf('%s[0]', $targetPdfPath),
            sprintf('%s:%s', $this->imageFormat, $targetPreviewPath)
        ];
        $pb = new ProcessBuilder($arguments);
        $process = $pb->getProcess();
        $code = $process->run();

        if($this->logger !== null) {
            $this->logger->debug($process->getCommandLine());
            $this->logger->debug(sprintf('%d - %s', $code, $process->getOutput()));
            $this->logger->debug(sprintf('%d - %s', $code, $process->getErrorOutput()));
        }

        unlink($targetPdfPath);

        return file_exists($targetPreviewPath);
    }

}