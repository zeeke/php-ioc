<?php

/**
 * User: zeeke
 * Date: 1/18/14
 * Time: 2:01 AM
 */

namespace laborra\ioc\holders;

class ContextOptionsHolder
{
    private $config;

    function __construct ($config)
    {
        $this->config = $config;
    }

    public function getBaseNameSpace ()
    {
        return $this->config['baseNamespace'] ? : null;
    }
}
