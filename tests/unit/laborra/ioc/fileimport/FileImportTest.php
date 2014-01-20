<?php

namespace laborra\ioc\fileimport;

use laborra\ioc\AppContext;
use laborra\ioc\ContextFactory;

class FileImportTest extends \PHPUnit_Framework_TestCase
{
    public function testPHP ()
    {
        $this->checkBeanImport(
            ContextFactory::buildFromFile(__DIR__.'/conf/config.php')
        );
    }

    public function checkBeanImport (AppContext $context)
    {
        $bean = $context->getBean('importedBean');
        $this->assertEquals(
            84,
            $bean->value
        );

        $this->assertEquals(
            $context->getBean('beanWithImportedRef')->refBean,
            $context->getBean('importedBean')
        );

        $this->assertEquals(
            42,
            $context->getBean('basicBeanRef')->refBean->value,
            $context->getBean('importedBean')
        );


    }
}

class BasicClass
{
    public $value;
}

class RefClass
{
    /** @var BasicClass */
    public $refBean;
}
