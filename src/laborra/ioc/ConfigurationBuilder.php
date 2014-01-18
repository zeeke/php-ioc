<?php

namespace laborra\ioc;

class ConfigurationBuilder
{
    /** array $config */
    private $config;

    public function __construct ()
    {
        $this->config = [
            'context' => [],
            'params' => [],
            'aspects' => [],
            'beans' => [],
        ];
    }

    public function bean ($beanId, $class)
    {
        $this->config['beans'][$beanId] = [
            'class' => $class,
        ];

        return new BeanDefinitionBuilder(
            $this,
            $this->config['beans'][$beanId]
        );
    }

    public function build ()
    {
        return $this->config;
    }
}

class BeanDefinitionBuilder
{
    /** ConfigurationBuilder $confBuilder */
    private $confBuilder;

    /** array $config */
    private $config;

    public function __construct (ConfigurationBuilder $confBuilder, array &$config)
    {
        $this->confBuilder = $confBuilder;
        $this->config = $config;
    }

    public function property ($propName, $value)
    {
        $this->initSection('properties');
        $this->config['properties'][$propName] = $value;
        return $this;
    }

    private function initSection ($sectionName)
    {
        if (isset($this->config[$sectionName])) {
            return;
        }

        $this->config[$sectionName] = [];
    }

    public function constructorArg ($argValue)
    {
        // TODO
    }

    public function call ($methodName, $args = [])
    {
        // TODO
    }
}

