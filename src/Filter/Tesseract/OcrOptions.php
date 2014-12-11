<?php

namespace Asoc\Dadatata\Filter\Tesseract;

use Asoc\Dadatata\Filter\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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

    protected function setOptionalOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setOptional(
            [
                self::OPTION_LANGUAGE
            ]
        );
    }
}