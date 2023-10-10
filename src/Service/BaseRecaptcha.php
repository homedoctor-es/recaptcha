<?php

namespace Kuttumiah\Recaptcha\Service;

use Illuminate\Support\Arr;

abstract class BaseRecaptcha implements RecaptchaInterface
{

    /**
     * @param array $config
     */
    public function __construct($config)
    {
        $this->config = $config;
    }

    /**
     * Get an item from an array using "dot" notation.
     *
     * @param \ArrayAccess|array $array
     * @param string|int|null $key
     * @param mixed $default
     * @return mixed
     */
    public function getConfigByKey($key, $default = null)
    {
        return Arr::get($this->config, $key, $default);
    }

}