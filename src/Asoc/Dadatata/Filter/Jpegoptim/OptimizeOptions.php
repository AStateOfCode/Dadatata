<?php

namespace Asoc\Dadatata\Filter\Jpegoptim;

use Asoc\Dadatata\Filter\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OptimizeOptions extends Options
{
    const OPTION_QUALITY = 'quality';
    const OPTION_THRESHOLD = 'threshold';
    const OPTION_ALL = 'all';
    const OPTION_STRIP = 'all';

    public function getQuality()
    {
        return $this->options[self::OPTION_QUALITY];
    }

    public function getThreshold()
    {
        return $this->options[self::OPTION_THRESHOLD];
    }

    public function getAll()
    {
        return $this->options[self::OPTION_ALL];
    }

    public function getStrip()
    {
        return $this->options[self::OPTION_STRIP];
    }

    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            [
                self::OPTION_STRIP => 'all'
            ]
        );
        $resolver->setRequired(
            [
                self::OPTION_QUALITY
            ]
        );
        $resolver->setOptional(
            [
                self::OPTION_THRESHOLD,
                self::OPTION_ALL,
                self::OPTION_STRIP
            ]
        );
        $resolver->setAllowedValues(
            [
                self::OPTION_ALL   => ['normal', 'progressive'],
                self::OPTION_STRIP => ['all', 'com', 'exif', 'iptc', 'icc']
            ]
        );
    }
}