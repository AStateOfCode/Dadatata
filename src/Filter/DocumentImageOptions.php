<?php

namespace Asoc\Dadatata\Filter;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DocumentImageOptions
 *
 * @package Asoc\Dadatata\Filter
 */
class DocumentImageOptions extends ImageOptions
{
    const OPTION_PAGES = 'pages';
    const OPTION_DENSITY = 'density';

    public function getPages()
    {
        return $this->options[self::OPTION_PAGES];
    }

    public function getDensity()
    {
        return $this->options[self::OPTION_DENSITY];
    }

    protected function setDefaultOptions(OptionsResolver $resolver)
    {
        parent::setDefaultOptions($resolver);

        $resolver->setDefaults(
            [
                self::OPTION_FORMAT  => self::FORMAT_JPG,
                self::OPTION_QUALITY => self::JPG_QUALITY_GOOD,
                self::OPTION_PAGES   => 'all',
                self::OPTION_DENSITY => 150
            ]
        );
    }

    protected function setRequiredOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(
            [
                self::OPTION_FORMAT,
                self::OPTION_QUALITY
            ]
        );
    }

    protected function setOptionalOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(
            [
                self::OPTION_WIDTH,
                self::OPTION_HEIGHT,
                self::OPTION_PAGES,
                self::OPTION_DENSITY
            ]
        );
    }
}
