<?php

namespace laborra\spring;

/**
 * @runTestsInSeparateProcesses
 */
class AppContextTest extends \PHPUnit_Framework_TestCase
{
    private static $CONF = [
        'beanOne' => [
            'class' => '\laborra\spring\ClassOne',
            'constructorArgs' => ['1', 10],
            'refProperties' => [
                'beanTwoRef' => 'beanTwo',
            ],
            'valProperties' => [
                'prop1' => 20,
                'prop2' => 'example string',
            ],
        ],

        'beanTwo' => [
            'class' => '\laborra\spring\ClassTwo',
            'valProperties' => [
                'prop1' => 42,
            ],
        ],

        'dontRef' => [
            'class' => '\laborra\spring\DontRef',
            'refProperties' => [
                'dontLoad' => 'dontLoad',
            ],
        ],

        'dontLoad' => [
            'class' => '\laborra\spring\DontLoadClass',
        ]
    ];

    public function testBasic ()
    {
        AppContext::init(self::$CONF);

        $bean1 = AppContext::get()->getBean('beanOne');

        $this->assertEquals($bean1->prop1, 20);
        $this->assertEquals($bean1->prop2, 'example string');
        $this->assertEquals($bean1->arg1, '1');
        $this->assertEquals($bean1->arg2, 10);
        $this->assertEquals($bean1->beanTwoRef->prop1, 42);
    }

    public function testSingleton ()
    {
        AppContext::init(self::$CONF);
        $bean1 = AppContext::get()->getBean('beanOne');
        $bean2 = AppContext::get()->getBean('beanOne');

        $this->assertEquals($bean2, $bean1);
    }

    public function testLazyLoading ()
    {
        AppContext::init(self::$CONF);
        $dontRefBean = AppContext::get()->getBean('dontRef');
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

