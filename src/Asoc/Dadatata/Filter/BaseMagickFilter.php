<?php


namespace Asoc\Dadatata\Filter;

use Asoc\Dadatata\Model\ImageInterface;
use Asoc\Dadatata\Model\ThingInterface;
use Symfony\Component\Process\ProcessBuilder;

abstract class BaseMagickFilter extends BaseImageFilter {

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
     * thumbnail or empty
     *
     * @var string
     */
    protected $mode;

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
     * @param string $mode
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
    }

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
    }

    /**
     * @param ThingInterface|ImageInterface $thing
     * @param string $sourcePath
     * @return string
     */
    protected function firstToImage(ThingInterface $thing, $sourcePath, array $options = null) {
        $tmpPath = tempnam(sys_get_temp_dir(), 'Dadatata');

        $pb = $this->getConvertProcess();
        $pb->add('-quality')->add($this->quality);

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

        if($this->mode === self::MODE_THUMBNAIL) {
            if($thing->getWidth() > $width && $thing->getHeight() > $height) {
                if($width === $height) {
                    // square image
                    // http://www.imagemagick.org/Usage/thumbnails/#cut
                    $single = sprintf('%dx%d', $width, $height);
                    $doubled = sprintf('%dx%d', $width*2, $height*2);
                    //$pb->add('-define')->add(sprintf('jpeg:size=%s', $doubled));
                    $pb->add('-thumbnail')->add(sprintf('%s^', $single));
                    $pb->add('-gravity')->add('center');
                    $pb->add('-extent')->add($single);
                }
                else {
                    $pb->add('-thumbnail')->add(sprintf('%dx%d', $width, $height));
                }
            }
        }
        else {
            list($resizeRatio, $width, $height) = $this->getProperSize($width, $height, $thing->getWidth(), $thing->getHeight());

            // only perform a resize when width and/or height changed
            if($thing->getWidth() !== $width || $thing->getHeight() !== $height) {
                $pb->add('-resize')->add(sprintf('%dx%d', $width, $height));
            }
        }

        if($this->format === 'webp') {
            $pb->add('-define')->add('webp:lossless=false');
        }

        $pb->add(sprintf('%s[0]', $sourcePath));
        $pb->add(sprintf('%s:%s', $this->format, $tmpPath));

        $process = $pb->getProcess();
        $code = $process->run();

        $x = $process->getOutput();
        $y = $process->getErrorOutput();

        return [$tmpPath];
    }

    /**
     * @return ProcessBuilder
     */
    protected abstract function getConvertProcess();

    protected function init() {
        $this->quality = self::JPG_QUALITY_GOOD;
        $this->format = self::FORMAT_JPG;
        $this->width = 320;
        $this->height = 240;
    }

} 