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

    function __construct (
        IParametersHolder $paramHolder,
        IBeanFactory $beanFactory)
    {
        $this->paramHolder = $paramHolder;
        $this->beanFactory = $beanFactory;
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