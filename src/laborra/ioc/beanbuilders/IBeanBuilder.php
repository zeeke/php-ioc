<?php
/**
 * User: zeeke
 * Date: 1/16/14
 * Time: 7:52 PM
 */
namespace laborra\ioc\beanbuilders;

interface IBeanBuilder
{
    public function build ($beanId, array $config);
}
