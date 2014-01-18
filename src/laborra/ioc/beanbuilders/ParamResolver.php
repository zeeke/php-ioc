<?php
/**
 * User: zeeke
 * Date: 1/16/14
 * Time: 7:48 PM
 */
namespace laborra\ioc\beanbuilders;

use laborra\ioc\IParametersHolder;

class ParamResolver extends BeanBuilderProxy
{
    /** IParametersHolder $paramHolder */
    private $paramHolder;

    public function __construct (IBeanBuilder $delegate, IParametersHolder $paramHolder)
    {
        parent::__construct($delegate);
        $this->paramHolder = $paramHolder;
    }

    public function build ($beanId, array $config)
    {
        foreach ($config['properties'] as $key => $value) {
            $this->resolveParameter($config['properties'], $key, $value);
        }

        foreach ($config['constructorArgs'] as $key => $value) {
            $this->resolveParameter($config['constructorArgs'], $key, $value);
        }

        foreach ($config['calls'] as $methodName => $arguments) {
            if (!is_array($arguments)) {
                // Method call with no arguments
                continue;
            }
            foreach ($arguments as $key => $argument) {
                $this->resolveParameter($config['calls'][$methodName], $key, $argument);
            }
        }

        return $this->delegate->build($beanId, $config);
    }

    private function resolveParameter (&$config, $key, $value)
    {
        if (!is_string($value)) {
            return;
        }

        if (substr($value, 0, 1) != '%') {
            return;
        }

        if (substr($value, -1, 1) != '%') {
            return;
        }

        $config[$key] = $this->paramHolder
            ->getParameter(substr($value, 1, -1));
    }

}