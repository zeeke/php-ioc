<?php
/**
 * User: zeeke
 * Date: 1/18/14
 * Time: 2:05 AM
 */
namespace laborra\ioc\beanbuilders;

class BaseNameSpaceResolver extends BeanBuilderProxy
{
    private $namespace;

    function __construct (IBeanBuilder $delegate, $namespace)
    {
        parent::__construct($delegate);
        $this->namespace = $namespace;
    }

    public function build ($beanId, array $config)
    {

        if (substr($config['class'], 0, 2) == '#\\') {
            $config['class'] = $this->namespace . '\\' . ltrim($config['class'], '#\\');
        }

        return $this->delegate->build($beanId, $config);
    }
}