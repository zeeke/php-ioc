<?php
/**
 * User: zeeke
 * Date: 1/16/14
 * Time: 7:49 PM
 */
namespace laborra\ioc\beanbuilders;

use laborra\ioc\BadConfigurationException;
use laborra\ioc\beanbuilders\BeanBuilderProxy;

class SyntaxChecker extends BeanBuilderProxy
{
    public function build ($beanId, array $config)
    {
        if (!is_string($config['class'])) {
            throw new BadConfigurationException('Class name must be a string. ' . $config['class'] . ' found.');
        }

        return $this->delegate->build($beanId, $config);
    }
}