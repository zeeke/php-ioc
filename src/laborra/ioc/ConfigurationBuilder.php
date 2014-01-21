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

    public function lazyLoading ($value)
    {
        $this->config['context']['lazyLoadging'] = $value;
    }

    public function baseNamespace ($baseNamespace)
    {
        $this->config['context']['baseNamespace'] = $baseNamespace;
    }

    public function param ($paramName, $paramValue)
    {
        $this->config['parameters'][$paramName] = "$paramValue";
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
        $this->config = &$config;
    }

    public function property ($propName, $value)
    {
        $this->initSection('properties');
        $this->config['properties'][$propName] = $value;
        return $this;
    }

    /**
     * @param string $sectionName
     */
    private function initSection ($sectionName)
    {
        if (isset($this->config[$sectionName])) {
            return;
        }

        $this->config[$sectionName] = [];
    }

    public function constructorArg ($argValue)
    {
        $this->initSection('constructorArgs');
        $this->config['constructorArgs'][] = $argValue;
        return $this;
    }

    public function constructorArgs (array $args)
    {
        $this->initSection('constructorArgs');
        $this->config['constructorArgs'] = $args;
        return $this;
    }

    public function call ($methodName, array $args = [])
    {
        $this->initSection('calls');
        $this->config['calls'][$methodName] = $args;
        return $this;
    }
}

