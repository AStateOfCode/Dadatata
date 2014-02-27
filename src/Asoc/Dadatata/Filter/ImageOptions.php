<?php

namespace Asoc\Dadatata\Filter;


use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ImageOptions extends Options {

    const OPTION_FORMAT = 'format';
    const OPTION_WIDTH = 'width';
    const OPTION_HEIGHT = 'height';
    const OPTION_QUALITY = 'quality';
    const OPTION_THUMBNAIL = 'thumbnail';

    const FORMAT_JPG = 'jpeg';
    const FORMAT_PNG = 'png';
    const FORMAT_WEBP = 'webp';

    const JPG_QUALITY_BEST = 100;
    const JPG_QUALITY_FINE = 90;
    const JPG_QUALITY_GOOD = 80;
    const JPG_QUALITY_AVERAGE = 50;

    const PNG_QUALITY_BEST = 0;
    const PNG_QUALITY_FINE = 3;
    const PNG_QUALITY_GOOD = 6;
    const PNG_QUALITY_AVERAGE = 9;

    const WEBP_QUALITY_BEST = 100;
    const WEBP_QUALITY_FINE = 90;
    const WEBP_QUALITY_GOOD = 80;
    const WEBP_QUALITY_AVERAGE = 50;

    public function getHeight() {
        return $this->options[self::OPTION_HEIGHT];
    }

    public function getWidth() {
        return $this->options[self::OPTION_WIDTH];
    }

    public function getFormat() {
        return $this->options[self::OPTION_FORMAT];
    }

    public function getQuality() {
        return $this->options[self::OPTION_QUALITY];
    }

    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
           self::OPTION_FORMAT => self::FORMAT_JPG,
           self::OPTION_QUALITY => self::JPG_QUALITY_GOOD
        ]);
        $resolver->setRequired([
            self::OPTION_FORMAT,
            self::OPTION_QUALITY,
            self::OPTION_WIDTH,
            self::OPTION_HEIGHT
        ]);
        $resolver->setOptional([
            self::OPTION_THUMBNAIL
        ]);
    }
} 