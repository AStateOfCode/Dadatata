<?php

namespace Asoc\Dadatata\Filter;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PassOptions
 *
 * @package Asoc\Dadatata\Filter
 */
class PassOptions extends Options
{
    const OPTION_MIME = 'mime';
    const OPTION_CATEGORY = 'category';

    public function passIfMime()
    {
        return isset($this->options['mime']);
    }

    public function passIfCategory()
    {
        return isset($this->options['category']);
    }

    public function getMime()
    {
        return $this->options['mime'];
    }

    public function getCategory()
    {
        return $this->options['category'];
    }

    protected function setOptionalOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(
            [
                self::OPTION_CATEGORY,
                self::OPTION_MIME
            ]
        );
    }
}
