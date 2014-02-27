<?php

namespace Asoc\Dadatata\Filter;


use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DocumentOptions extends Options {

    const OPTION_FORMAT = 'format';

    public function getFormat() {
        return $this->options[self::OPTION_FORMAT];
    }

    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired([
            self::OPTION_FORMAT
        ]);
    }
}