<?php

namespace Asoc\Dadatata\Filter\Php;

use Asoc\Dadatata\Exception\ProcessingFailedException;
use Asoc\Dadatata\Filter\BaseImageFilter;
use Asoc\Dadatata\Filter\OptionsInterface;
use Asoc\Dadatata\Model\ImageInterface;
use Asoc\Dadatata\Model\ThingInterface;
use Imagine\Image\Box;
use Imagine\Image\ImagineInterface;

class ImagineResize extends BaseImageFilter {

    /**
     * @var string
     */
    protected $bin;

    /**
     * @var \Imagine\Image\ImagineInterface
     */
    private $imagine;

    public function __construct(ImagineInterface $imagine) {
        $this->imagine = $imagine;
    }

    /**
     * @param ThingInterface $thing
     * @param $sourcePath
     * @param OptionsInterface $options
     * @return array Paths to generated files
     */
    public function process(ThingInterface $thing, $sourcePath, OptionsInterface $options = null)
    {
        $image = $this->imagine->open($sourcePath);

        $options = $this->defaults->merge($options);

        $width = $options->getWidth();
        $height = $options->getHeight();

        $size = new Box($width, $height);

        $transformation = $this->getTransformation($image, $size);

        $tmpPath = tempnam(sys_get_temp_dir(), 'Dadatata');
        $transformation->save($tmpPath, [
            'format' => $options->getFormat(),
            'quality' => $options->getQuality()
        ]);

        try {
            $transformation->apply($image);
        }
        catch(\Exception $e) {
            throw new ProcessingFailedException('Failed to create image: '.$e->getMessage());
        }

        return [$tmpPath];
    }

    protected function getTransformation(\Imagine\Image\ImageInterface $image, Box $size, OptionsInterface $options = null) {
        $transformation =  new \Imagine\Filter\Transformation();
        $transformation->resize($size, \Imagine\Image\ImageInterface::FILTER_LANCZOS);
        return $transformation;
    }

    /**
     * @param ThingInterface $thing
     * @return boolean
     */
    public function canHandle(ThingInterface $thing)
    {
        return $thing instanceof ImageInterface;
    }
}