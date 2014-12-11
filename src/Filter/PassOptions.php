<?php

namespace Asoc\Dadatata\Filter;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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

    protected function setOptionalOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setOptional(
            [
                self::OPTION_CATEGORY,
                self::OPTION_MIME
            ]
        );
    }
}