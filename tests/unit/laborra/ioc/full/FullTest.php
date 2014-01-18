<?php

namespace laborra\ioc\full;

use laborra\ioc\AppContext;
use laborra\ioc\ContextFactory;

class FullTest extends \PHPUnit_Framework_TestCase
{

    public function testFull ()
    {
        $baseConfDir = __DIR__.'/../../../../conf/full/';
        $contexts = [
            ContextFactory::buildFromFile("$baseConfDir/config.php"),
            ContextFactory::buildFromFile("$baseConfDir/builder.php"),
//            ContextFactory::buildFromFile(__DIR__.'/../../../conf/full/config.yaml'),
        ];

        foreach ($contexts as $context) {
            $this->basicCheck($context);
            $this->constructorArgCheck($context);
            $this->setterClassCheck($context);
            $this->callsCheck($context);
        }
    }

    public function basicCheck (AppContext $context) {

        $this->assertInstanceOf(
            'laborra\ioc\full\BasicClass',
            $bean = $context->getBean('basicBean')
        );

        $this->assertInstanceOf(
            'laborra\ioc\full\BasicClass',
            $context->getBean('shortcutBean')
        );
    }

    public function constructorArgCheck (AppContext $context)
    {
        $bean = $context->getBean('constructorArgBean');
        $this->assertEquals('simple value', $bean->value);
        $this->assertEquals('parameterValue1', $bean->param);
        $this->assertEquals(
            $context->getBean('basicBean'),
            $bean->reference
        );
    }

    public function setterClassCheck (AppContext $context)
    {
        $bean = $context->getBean('setterBean');
        $this->assertEquals('simpleValue', $bean->getValue());
        $this->assertEquals('parameterValue1', $bean->getParam());
        $this->assertEquals(
            $context->getBean('basicBean'),
            $bean->getReference()
        );
    }

    public function callsCheck (AppContext $context)
    {
        $bean = $context->getBean('callsBean');
        $this->assertEquals(42, $bean->propertyOne);
        $this->assertEquals("foo - simpleValue", $bean->propertyTwo);
    }

    public function importedBeansCheck ($context)
    {
        $bean1 = $context->getBean('imported1');
        $this->assertEquals(
            $context->getBean('imported2'),
            $bean1->reference
        );
    }
}

class BasicClass
{
}

class ConstructorArgClass
{
    public $value;
    public $param;
    public $reference;

    public function __construct ($value, $param, $reference)
    {
        $this->value = $value;
        $this->param = $param;
        $this->reference = $reference;
    }
}

class SetterClass
{
    private $value;
    private $param;
    private $reference;

    public function setValue ($value)
    {
        $this->value = $value;
    }

    public function getValue ()
    {
        return $this->value;
    }

    public function setParam ($param)
    {
        $this->param = $param;
    }

    public function getParam ()
    {
        return $this->param;
    }

    public function setReference ($reference)
    {
        $this->reference = $reference;
    }

    public function getReference ()
    {
        return $this->reference;
    }
}

class CallClass
{
    public $propertyOne;
    public $propertyTwo;

    public function noArgsCall ()
    {
        $this->propertyOne = 42;
    }

    public function argsCall ($arg1, SetterClass $arg2)
    {
        $this->propertyTwo = "$arg1 - ".$arg2->getValue();
    }
}

