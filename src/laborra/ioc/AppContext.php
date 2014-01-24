<?php
/**
 * User: zeeke
 * Date: 1/18/14
 * Time: 2:09 AM
 */
namespace laborra\ioc;

class AppContext implements IBeanFactory, IParametersHolder
{
    /** IBeanFactory $beanFactory */
    private $beanFactory;

    /** IParametersHolder $paramHolder */
    private $paramHolder;

    /** @var  AppContext */
    private static $context;

    public function __construct (
        IParametersHolder $paramHolder,
        IBeanFactory $beanFactory)
    {
        $this->paramHolder = $paramHolder;
        $this->beanFactory = $beanFactory;
    }

    public static function init (AppContext $context)
    {
        self::$context = $context;
    }

    /**
     * @return AppContext
     */
    public static function get ()
    {
        return self::$context;
    }

    public function getBean ($beanId)
    {
        return $this->beanFactory->getBean($beanId);
    }

    public function getParameter ($paramName)
    {
        return $this->paramHolder->getParameter($paramName);
    }

}