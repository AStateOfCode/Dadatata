<?php

namespace Asoc\Dadatata\Filter;


interface OptionsInterface {

    /**
     * @param string $key
     * @param null $valueWhenUnset
     * @return mixed
     */
    public function get($key, $valueWhenUnset = null);

    /**
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value);

    /**
     * @param string $key
     * @return boolean
     */
    public function has($key);

    /**
     * @return array
     */
    public function all();

    /**
     * @param array|OptionsInterface $options
     * @return OptionsInterface
     */
    public function merge($options);

} 