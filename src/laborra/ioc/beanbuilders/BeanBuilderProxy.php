<?php
/**
 * User: zeeke
 * Date: 1/16/14
 * Time: 7:51 PM
 */
namespace laborra\ioc\beanbuilders;

use laborra\ioc\beanbuilders\IBeanBuilder;

abstract class BeanBuilderProxy implements IBeanBuilder
{
    /** IBeanBuilder $delegate */
    protected $delegate;

    public function __construct (IBeanBuilder $delegate)
    {
        $this->delegate = $delegate;
    }
}