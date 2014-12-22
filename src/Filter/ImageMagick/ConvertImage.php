<?php

namespace Asoc\Dadatata\Filter\ImageMagick;

use Asoc\Dadatata\Exception\ProcessingFailedException;
use Asoc\Dadatata\Filter\FilterInterface;
use Asoc\Dadatata\Filter\ImageOptions;
use Asoc\Dadatata\Filter\OptionsInterface;
use Asoc\Dadatata\Model\ImageInterface;
use Asoc\Dadatata\Model\ThingInterface;
use Asoc\Dadatata\Tool\ImageMagickConvert;
use Asoc\Dadatata\ToolInterface;
use Neutron\TemporaryFilesystem\TemporaryFilesystemInterface;

/**
 * Class ConvertImage
 *
 * @package Asoc\Dadatata\Filter\ImageMagick
 */
class ConvertImage implements FilterInterface
{
    /**
     * @var ToolInterface|ImageMagickConvert
     */
    private $convert;

    /**
     * @var TemporaryFilesystemInterface
     */
    private $tmpFs;

    /**
     * @var ImageOptions
     */
    private $defaults;

    /**
     * @param ToolInterface                $convert
     * @param TemporaryFilesystemInterface $tmpFs
     */
    public function __construct(ToolInterface $convert, TemporaryFilesystemInterface $tmpFs)
    {
        $this->convert = $convert;
        $this->tmpFs   = $tmpFs;
    }

    /**
     * @param ThingInterface                $thing
     * @param string                        $sourcePath
     * @param OptionsInterface|ImageOptions $options
     *
     * @throws \Asoc\Dadatata\Exception\ProcessingFailedException
     * @return array Paths to generated files
     */
    public function process(ThingInterface $thing, $sourcePath, OptionsInterface $options = null)
    {
        $outputFile = $this->tmpFs->createTemporaryFile();

        $pb = $this->convert->getProcessBuilder();

        $options = $this->defaults->merge($options);

        if ($options->has($options::OPTION_FORMAT)) {
            $pb->format($options->getFormat());
        }
        if ($options->has($options::OPTION_QUALITY)) {
            $pb->quality($options->getQuality());
        }

        $pb->source($sourcePath)->output($outputFile);

        $process = $pb->getProcess();

        $code = $process->run();
        if ($code !== 0) {
            throw ProcessingFailedException::create(
                'Failed to convert image.',
                $code,
                $process->getOutput(),
                $process->getErrorOutput()
            );
        }

        return [$outputFile];
    }

    /**
     * @param ThingInterface $thing
     *
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
        if (!($options instanceof ImageOptions)) {
            $options = new ImageOptions($options->all());
        }
        $this->defaults = $options;
    }
}