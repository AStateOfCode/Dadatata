<?php

namespace Asoc\Dadatata\Filter\Jpegoptim;

use Asoc\Dadatata\Filter\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class OptimizeOptions
 *
 * @package Asoc\Dadatata\Filter\Jpegoptim
 */
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

    protected function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                self::OPTION_STRIP => 'all'
            ]
        );

        $resolver->setAllowedValues(self::OPTION_ALL, [
            'normal',
            'progressive'
        ]);

        $resolver->setAllowedValues(self::OPTION_STRIP, [
            'all',
            'com',
            'exif',
            'iptc',
            'icc'
        ]);
    }

    protected function setRequiredOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(
            [
                self::OPTION_QUALITY
            ]
        );
    }

    protected function setOptionalOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(
            [
                self::OPTION_THRESHOLD,
                self::OPTION_ALL,
                self::OPTION_STRIP
            ]
        );
    }
}
