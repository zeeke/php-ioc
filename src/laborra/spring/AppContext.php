<?php

namespace laborra\spring;

class AppContext implements IBeanFactory
{
    private $beanFactory;

    private function __construct (IBeanFactory $beanFactory)
    {
        $this->beanFactory = $beanFactory;
    }

    public static function init ($config)
    {
        if (self::$instance != null) {
            throw new \Exception("Application context already initialized");
        }

        $configuration = null;
        if (is_string($config)) {
            $configuration = BeansConfiguration::createFromFile($config);
        } else if (is_array($config)) {
            $configuration = BeansConfiguration::createFromArray($config);
        } else {
            throw new \Exception("Cannot load configuration");
        }

        self::$instance = new AppContext(
                new BeansContainer($configuration));
        return self::$instance;
    }

    private static $instance = null;

    public static function get ()
    {
        if (self::$instance == null) {
            throw new \Exception("Application context not initialized!");
        }

        return self::$instance;
    }

    public static function bean ($beanId)
    {
        return self::get()->getBean($beanId);
    }

    public function getBean ($beanId)
    {
        return $this->beanFactory->getBean($beanId);
    }
}

interface IBeanFactory
{
    public function getBean ($beanId);
}

class BeansContainer implements IBeanFactory
{
    private $factory;
    private $beans = array();

    public function __construct (IBeanFactory $factory)
    {
        $this->factory = $factory;
    }

    public function getBean ($beanId)
    {
        if (!isset($this->beans[$beanId])) {
            $this->beans[$beanId] = $this->factory->getBean($beanId);
        }

        return $this->beans[$beanId];
    }
}

class BeansConfiguration implements IBeanFactory
{
    private $configuration = array();

    public function __construct (array $configuration)
    {
        $this->configuration = $configuration;
    }

    public static function createFromFile ($confFile)
    {
        return self::createFromArray(include($confFile));
    }

    public static function createFromArray (array $confArray)
    {
        return new BeansConfiguration($confArray);
    }

    public function getBean ($beanId)
    {
        if (!isset($this->configuration[$beanId])) {
            throw new \Exception("Bean $beanId not found in configuration");
        }

        return BeanBuilder::createBean($this->configuration[$beanId]);
    }
}


class BeanBuilder
{
    public $constructorArgs = array();
    public $valProperties = array();
    public $refProperties = array();
    public $className = "";

    private $appContext;

    public static function createBean (array $config)
    {
        $beanBuilder = new BeanBuilder();

        $beanBuilder->className = $config['class'];

        if (isset($config['constructorArgs'])) {
            $beanBuilder->constructorArgs = $config['constructorArgs'];
        }

        if (isset($config['valProperties'])) {
            $beanBuilder->valProperties = $config['valProperties'];
        }

        if (isset($config['refProperties'])) {
            $beanBuilder->refProperties = $config['refProperties'];
        }

        return $beanBuilder->build();
    }

    public function build ()
    {
        $reflection = new \ReflectionClass($this->className);
        $bean = $reflection->newInstanceArgs($this->constructorArgs);
        foreach ($this->valProperties as $key => $value) {
            $bean->{$key} = $value;
        }

        foreach ($this->refProperties as $key => $value) {
            $bean->{$key} = new BeanProxy($value, AppContext::get());
        }

        return $bean;
    }

    public function validate ()
    {
        if ($this->className == null) {
            throw new \Exception("Bad bean configuration: a class name is required!");
        }
    }
}

class BeanProxy
{
    private $targetId;
    private $target = null;
    private $beanFactory;

    public function __construct ($targetId, IBeanFactory $beanFactory)
    {
        $this->targetId = $targetId;
        $this->beanFactory = $beanFactory;
    }

    private function load ()
    {
        if ($this->target === null) {
            $this->target = $this->beanFactory->getBean($this->targetId);
        }
    }

    public function __call ($name, $args)
    {
        $this->load();
        return call_user_func($this->target, $args);
    }

    public function __get ($name)
    {
        $this->load();
        return $this->target->{$name};
    }

    public function __set ($name, $value)
    {
        $this->load();
        $this->target->{$name} = $value;
    }
}

