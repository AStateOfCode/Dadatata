<?php

namespace Asoc\Dadatata\Filter\Php;

use Asoc\Dadatata\Filter\BaseImageFilter;
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
     * @var string
     */
    protected $format;
    /**
     * @var int
     */
    protected $quality;
    /**
     * @var int
     */
    protected $width;
    /**
     * @var int
     */
    protected $height;

    /**
     * @param int $height
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * @param int $width
     */
    public function setWidth($width)
    {
        $this->width = $width;
    }

    /**
     * @param int $quality
     */
    public function setQuality($quality)
    {
        $this->quality = $quality;
    }

    /**
     * @param string $format
     */
    public function setFormat($format)
    {
        $this->format = $format;
    }

    /**
     * @var \Imagine\Image\ImagineInterface
     */
    private $imagine;

    public function __construct(ImagineInterface $imagine) {
        $this->imagine = $imagine;

        $this->init();
    }

    protected function init() {
        $this->quality = self::JPG_QUALITY_GOOD;
        $this->format = self::FORMAT_JPG;
        $this->width = 320;
        $this->height = 240;
    }

    /**
     * @param ThingInterface $thing
     * @param $sourcePath
     * @param array $options
     * @return array Paths to generated files
     */
    public function process(ThingInterface $thing, $sourcePath, array $options = null)
    {
        $image = $this->imagine->open($sourcePath);

        $width = $this->width;
        $height = $this->height;

        if($options !== null) {
            if(isset($options['width'])) {
                $width = $options['width'];
            }
            if(isset($options['height'])) {
                $height = $options['height'];
            }
        }

        $size = new Box($width, $height);

        $transformation = $this->getTransformation($image, $size);


        $tmpPath = tempnam(sys_get_temp_dir(), 'Dadatata');
        $transformation->save($tmpPath, [
            'format' => $this->format,
            'quality' => $this->quality
        ]);

        $transformation->apply($image);

        return [$tmpPath];
    }

    protected function getTransformation(\Imagine\Image\ImageInterface $image, Box $size, array $options = null) {
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

    /**
     * @param array $options
     */
    public function setOptions(array $options)
    {
        if(isset($options['width'])) {
            $this->width = $options['width'];
        }
        if(isset($options['height'])) {
            $this->height = $options['height'];
        }
        if(isset($options['format'])) {
            $this->format = $options['format'];
        }
        if(isset($options['quality'])) {
            $this->quality = $options['quality'];
        }
    }
}