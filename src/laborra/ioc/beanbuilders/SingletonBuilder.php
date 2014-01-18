<?php
/**
 * User: zeeke
 * Date: 1/18/14
 * Time: 2:05 AM
 */
namespace laborra\ioc\beanbuilders;

class SingletonBuilder extends BeanBuilderProxy
{
    private $singletons = [];

    public function build ($beanId, array $config)
    {
        if (!isset($this->singletons[$beanId])) {
            $this->singletons[$beanId] = $this->delegate
                ->build($beanId, $config);
        }

        return $this->singletons[$beanId];
    }
}
