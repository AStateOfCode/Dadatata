<?php

namespace Asoc\Dadatata\Filter\Tesseract;

use Asoc\Dadatata\Filter\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class OcrOptions extends Options
{
    const OPTION_LANGUAGE = 'language';

    public function getLanguage()
    {
        return $this->options[self::OPTION_LANGUAGE];
    }

    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setOptional(
            [
                self::OPTION_LANGUAGE
            ]
        );
    }
}