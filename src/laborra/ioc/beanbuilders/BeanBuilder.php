<?php
/**
 * User: zeeke
 * Date: 1/16/14
 * Time: 7:51 PM
 */
namespace laborra\ioc\beanbuilders;

use laborra\ioc\BadConfigurationException;

class BeanBuilder implements IBeanBuilder
{
    public $constructorArgs = array();
    public $valProperties = array();
    public $refProperties = array();
    public $className = "";

    public function build ($beanId, array $config)
    {
        $reflection = new \ReflectionClass($config['class']);
        $bean = $reflection->newInstanceArgs($config['constructorArgs']);

        foreach ($config['properties'] as $key => $value) {
            // TODO - use setter or public attribute access
            $setter = 'set'.ucfirst($key);
            if ($reflection->hasMethod($setter)) {
                call_user_func([$bean, $setter], $value);
                continue;
            }

            if ($reflection->hasProperty($key)) {
                $reflectionProp = $reflection->getProperty($key);
                if ($reflectionProp->isPublic()) {
                    $bean->{$key} = $value;
                    continue;
                }
            }

            throw new BadConfigurationException("Cannot set property $key on bean $beanId");
        }

        foreach ($config['calls'] as $key => $value) {
            if (is_numeric($key)) {
                $key = $value;
                $value = [];
            }
            call_user_func_array([$bean, $key], $value);
        }

        return $bean;
    }
}
