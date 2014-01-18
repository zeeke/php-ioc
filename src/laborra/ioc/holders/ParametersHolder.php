<?php
/**
 * User: zeeke
 * Date: 1/16/14
 * Time: 7:54 PM
 */
namespace laborra\ioc\holders;

use laborra\ioc\BadConfigurationException;
use laborra\ioc\IParametersHolder;

class ParametersHolder implements IParametersHolder
{
    private $config;

    public function __construct (array $config)
    {
        $this->config = $config;
    }

    public function getParameter ($paramName)
    {
        if (!isset($this->config[$paramName])) {
            throw new BadConfigurationException("Cannot find parameter $paramName");
        }
        return $this->config[$paramName];
    }
}