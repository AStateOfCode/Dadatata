<?php

namespace Asoc\Dadatata\Filter\Tesseract;

use Asoc\Dadatata\Filter\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class OcrOptions
 *
 * @package Asoc\Dadatata\Filter\Tesseract
 */
class OcrOptions extends Options
{
    const OPTION_LANGUAGE = 'language';

    public function getLanguage()
    {
        return $this->options[self::OPTION_LANGUAGE];
    }

    protected function setOptionalOptions(OptionsResolver $resolver)
    {
        $resolver->setDefined(
            [
                self::OPTION_LANGUAGE
            ]
        );
    }
}
