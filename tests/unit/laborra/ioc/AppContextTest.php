<?php

namespace laborra\ioc;

class AppContextTest extends \PHPUnit_Framework_TestCase
{
    private static $CONF = [
        'beanOne' => [
            'class' => '\laborra\ioc\ClassOne',
            'constructorArgs' => ['1', 10],
            'properties' => [
                'beanTwoRef' => '@beanTwo',
                'prop1' => 20,
                'prop2' => 'example string',
            ],
        ],

        'beanTwo' => [
            'class' => '\laborra\ioc\ClassTwo',
            'properties' => [
                'prop1' => 42,
            ],
        ],

        'dontRef' => [
            'class' => '\laborra\ioc\DontRef',
            'properties' => [
                'dontLoad' => '@dontLoad',
            ],
        ],

        'dontLoad' => [
            'class' => '\laborra\ioc\DontLoadClass',
        ]
    ];

    /** @var  AppContext $context */
    private $context;

    protected function setUp ()
    {
        $this->context = ContextFactory::buildFromPHPArray(
            ['beans' => self::$CONF]);
    }

    public function testBasic ()
    {
        $bean1 = $this->context->getBean('beanOne');

        $this->assertEquals($bean1->prop1, 20);
        $this->assertEquals($bean1->prop2, 'example string');
        $this->assertEquals($bean1->arg1, '1');
        $this->assertEquals($bean1->arg2, 10);
        $this->assertEquals($bean1->beanTwoRef->prop1, 42);
    }

    public function testSingleton ()
    {
        $bean1 = $this->context->getBean('beanOne');
        $bean2 = $this->context->getBean('beanOne');

        $this->assertEquals($bean2, $bean1);
    }

    public function testLazyLoading ()
    {
        $this->markTestSkipped("Lazy loading is not yet implemented");

        $dontRefBean = $this->context->getBean('dontRef');
        $bean2 = $dontRefBean->dontLoad;

        try {
            $bean2->field1 = "access real bean";
            $this->fail("Contructor must be called");
        } catch (\Exception $e) {
            $this->assertEquals(
                "Called constructor of DontLoadClass",
                $e->getMessage());
        }
    }
}

class ClassOne
{
    public $prop1;
    public $prop2;

    public $arg1;
    public $arg2;

    public $beanTwoRef;

    public function __construct($arg1, $arg2)
    {
        $this->arg1 = $arg1;
        $this->arg2 = $arg2;
    }
}

class ClassTwo
{
    public $prop1;
}

class DontRef
{
    public $dontLoad;
}

class DontLoadClass
{
    public $field1 = "field1";

    public function __construct ()
    {
        throw new \Exception("Called constructor of DontLoadClass");
    }
}

