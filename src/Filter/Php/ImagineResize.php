<?php

namespace Asoc\Dadatata\Filter\Php;

use Asoc\Dadatata\Exception\ProcessingFailedException;
use Asoc\Dadatata\Filter\BaseImageFilter;
use Asoc\Dadatata\Filter\OptionsInterface;
use Asoc\Dadatata\Model\ImageInterface;
use Asoc\Dadatata\Model\ThingInterface;
use Imagine\Image\Box;
use Imagine\Image\ImagineInterface;
use Neutron\TemporaryFilesystem\TemporaryFilesystemInterface;

class ImagineResize extends BaseImageFilter
{
    /**
     * @var string
     */
    protected $bin;

    /**
     * @var \Imagine\Image\ImagineInterface
     */
    private $imagine;

    /**
     * @var TemporaryFilesystemInterface
     */
    private $tmpFs;

    /**
     * @param \Imagine\Image\ImagineInterface $imagine
     * @param TemporaryFilesystemInterface    $tmpFs
     */
    public function __construct(ImagineInterface $imagine, TemporaryFilesystemInterface $tmpFs)
    {
        $this->imagine = $imagine;
        $this->tmpFs   = $tmpFs;
    }

    /**
     * @param ThingInterface   $thing
     * @param string           $sourcePath
     * @param OptionsInterface $options
     *
     * @return array Paths to generated files
     * @throws ProcessingFailedException
     */
    public function process(ThingInterface $thing, $sourcePath, OptionsInterface $options = null)
    {
        $image = $this->imagine->open($sourcePath);

        $options = $this->defaults->merge($options);

        $width  = $options->getWidth();
        $height = $options->getHeight();

        $size = new Box($width, $height);

        $transformation = $this->getTransformation($image, $size, $options);

        $tmpPath = $this->tmpFs->createTemporaryFile();
        $transformation->save(
            $tmpPath,
            [
                'format'  => $options->getFormat(),
                'quality' => $options->getQuality()
            ]
        );

        try {
            $transformation->apply($image);
        } catch (\Exception $e) {
            throw new ProcessingFailedException('Failed to create image: '.$e->getMessage());
        }

        return [$tmpPath];
    }

    protected function getTransformation(
        \Imagine\Image\ImageInterface $image,
        Box $size,
        OptionsInterface $options = null
    ) {
        $transformation = new \Imagine\Filter\Transformation();
        $transformation->resize($size, \Imagine\Image\ImageInterface::FILTER_LANCZOS);

        return $transformation;
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
}