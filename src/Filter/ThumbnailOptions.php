<?php

namespace Asoc\Dadatata\Filter;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ThumbnailOptions extends ImageOptions
{
    const OPTION_MODE = 'mode';

    const MODE_INSET = 'inset';
    const MODE_OUTBOUND = 'outbound';

    public function getMode()
    {
        return $this->options[self::OPTION_MODE];
    }

    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(
            [
                self::OPTION_MODE => self::MODE_OUTBOUND
            ]
        );
        $resolver->setAllowedValues(
            [
                self::OPTION_MODE => [self::MODE_OUTBOUND, self::MODE_INSET]
            ]
        );
    }
} 