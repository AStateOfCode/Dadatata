<?php

namespace Asoc\Dadatata\Filter;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\OptionsResolver\Options as ResolvedOptions;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class Options implements OptionsInterface {

    /**
     * @var array
     */
    protected $options;

    public function __construct(array $options = []) {
        $resolver = new OptionsResolver();
        $this->setDefaultOptions($resolver);

        $this->options = $resolver->resolve($options);
    }

    public function merge($options) {
        if($options === null) {
            return $this;
        }

        $all = null;
        if($options instanceof OptionsInterface) {
            $all = $options->all();
        }
        else if(is_array($options)) {
            $all = $options;
        }
        else {
            throw new \InvalidArgumentException(sprintf('Given options to merge are not supported: %s', gettype($options)));
        }

        return new self(array_replace_recursive([], $this->options, $all));
    }

    public function all() {
        return $this->options;
    }

    public function set($key, $value) {
        $this->options[$key] = $value;
    }

    public function get($key, $valueWhenUnset = null)
    {
        if(isset($this->options[$key])) {
            return $this->options[$key];
        }

        return $valueWhenUnset;
    }

    public function has($key) {
        return isset($this->options[$key]);
    }

    protected function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    }
}