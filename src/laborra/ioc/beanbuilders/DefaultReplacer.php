<?php
/**
 * User: zeeke
 * Date: 1/16/14
 * Time: 7:51 PM
 */
namespace laborra\ioc\beanbuilders;

use laborra\ioc\beanbuilders\BeanBuilderProxy;

class DefaultReplacer extends BeanBuilderProxy
{
    public function build ($beanId, array $config)
    {
        // TODO - Check missing configuration
        if (isset($config[0]) && !isset($config['class'])) {
            $config['class'] = $config[0];
        }

        if (!isset($config['constructorArgs'])) {
            $config['constructorArgs'] = [];
        }

        if (!isset($config['properties'])) {
            $config['properties'] = [];
        }

        if (!isset($config['calls'])) {
            $config['calls'] = [];
        }

        return $this->delegate->build($beanId, $config);
    }
}