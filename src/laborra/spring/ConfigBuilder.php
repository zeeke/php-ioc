<?php

namespace laborra\spring;

class ConfigBuilder
{
    public $config = [];

    public function __call ($name, $arguments) {
        if (count($arguments) == 1) {
            $this->config[$name] = [ 'class' => $arguments[0] ];
        }
        return $this;
    }

    public function bean ($name, $class)
    {
        return new BeanConfigBuilder($name, $class, $this);
    }
}

class BeanConfigBuilder
{
    private $config;
    private $name;
    private $configBuilder;

    public function __construct ($name, $class, ConfigBuilder $configBuilder)
    {
        $this->name = $name;
        $this->config = ['class' => $class];
        $this->configBuilder = $configBuilder;
    }

    public function ref ($property, $refId = null) {
        if ($refId == null) {
            $refId = $property;
        }
        if (!isset($this->config['refProperties'])) {
            $this->config['refProperties'] = [];
        }
        $this->config['refProperties'][$property] = $refId;
        return $this;
    }

    public function val ($property, $value) {
        if (!isset($this->config['valProperties'])) {
            $this->config['valProperties'] = [];
        }
        $this->config['valProperties'][$property] = $value;
        return $this;
    }

    public function build ()
    {
        $this->configBuilder->config[$this->name] = $this->config;
        return $this->configBuilder;
    }
}
