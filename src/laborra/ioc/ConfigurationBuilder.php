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
            'imports' => [],
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
        $this->config['context']['lazyLoading'] = $value;
    }

    public function baseNamespace ($baseNamespace)
    {
        $this->config['context']['baseNamespace'] = $baseNamespace;
    }

    public function param ($paramName, $paramValue)
    {
        $this->config['parameters'][$paramName] = "$paramValue";
    }

    public function import ($resource)
    {
        $this->config['imports'][] = $resource;
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

    public function property ($propName, $value = null)
    {
        if ($value == null) {
            if (substr($propName, 0, 1) == '@') {
                $value = $propName;
                $propName = ltrim($propName, '@');
            }
        }

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

