<?php

use laborra\ioc\ConfigurationBuilder;

$c = new ConfigurationBuilder();

$c->lazyLoading(true);
$c->baseNamespace('laborra\ioc\full');

$c->param('parameterName1', 'parameterValue1');

$c->bean('basicBean', '\laborra\ioc\full\BasicClass');

// There isn't a shortcut for builder based file
$c->bean('shortcutBean', '\laborra\ioc\full\BasicClass');


$c->bean('constructorArgBean', '#\ConstructorArgClass')
    ->constructorArg('simple value')
    ->constructorArg('%parameterName1%')
    ->constructorArg('@basicBean');
// Alternative
// $c->bean('constructorArgBean', '#\ConstructorArgClass')
//     ->constructorArgs(['simple_value', '%parameterName1%', '@basicBean']);

$c->bean('setterBean', '#\SetterClass')
    ->property('value', 'simpleValue')
    ->property('param', '%parameterName1%')
    ->property('reference', '@basicBean');

$c->bean('callsBean', '#\CallClass')
    ->call('noArgsCall', []) // The second parameter is not required
    ->call('argsCall', ['foo', '@setterBean']);

return $c->build();

