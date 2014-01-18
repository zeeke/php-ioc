<?php
/**
 * User: zeeke
 * Date: 1/18/14
 * Time: 2:09 AM
 */
namespace laborra\ioc\holders;

class BeanDefinitionsHolder
{
    private $beanDefinitions;

    public function __construct (array $beanDefinitions)
    {
        $this->beanDefinitions = $beanDefinitions;
    }

    public function getBeanDefinition ($beanId)
    {
        // TODO - check bean presence
        return $this->beanDefinitions[$beanId];
    }
}