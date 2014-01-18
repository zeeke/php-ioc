<?php
/**
 * User: zeeke
 * Date: 1/16/14
 * Time: 7:55 PM
 */
namespace laborra\ioc;

interface IBeanFactory
{
    public function getBean ($beanId);
}