<?php
/**
 * User: zeeke
 * Date: 1/16/14
 * Time: 7:50 PM
 */
namespace laborra\ioc\beanbuilders;

use laborra\ioc\beanbuilders\BeanBuilderProxy;
use laborra\ioc\beanbuilders\IBeanBuilder;
use laborra\ioc\IBeanFactory;

class ReferenceReplacer extends BeanBuilderProxy
{
    /** IBeanFactory $beanFactory */
    private $beanFactory;

    public function __construct (IBeanBuilder $delegate, IBeanFactory $beanFactory)
    {
        parent::__construct($delegate);
        $this->beanFactory = $beanFactory;
    }

    public function build ($beanId, array $config)
    {
        foreach ($config['properties'] as $key => $value) {
            $this->resolveReference($config['properties'], $key, $value);
        }

        foreach ($config['constructorArgs'] as $key => $value) {
            $this->resolveReference($config['constructorArgs'], $key, $value);
        }

        foreach ($config['calls'] as $methodName => $arguments) {
            if (!is_array($arguments)) {
                // Method call with no arguments
                continue;
            }
            foreach ($arguments as $key => $argument) {
                $this->resolveReference($config['calls'][$methodName], $key, $argument);
            }
        }

        return $this->delegate->build($beanId, $config);
    }

    private function resolveReference (&$config, $key, $value)
    {
        if (!is_string($value)) {
            return;
        }

        if (substr($value, 0, 1) != '@') {
            return;
        }

        $config[$key] = $this->beanFactory
            ->getBean(ltrim($value, '@'));
    }
}