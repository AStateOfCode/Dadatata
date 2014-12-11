<?php

namespace Asoc\Dadatata\Filter;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class DocumentOptions
 *
 * @package Asoc\Dadatata\Filter
 */
class DocumentOptions extends Options
{
    const OPTION_FORMAT = 'format';

    public function getFormat()
    {
        return $this->options[self::OPTION_FORMAT];
    }

    protected function setRequiredOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(
            [
                self::OPTION_FORMAT
            ]
        );
    }
}