<?php
/**
 * User: zeeke
 * Date: 1/18/14
 * Time: 2:09 AM
 */
namespace laborra\ioc;

use laborra\ioc\beanbuilders\IBeanBuilder;

class BeanFactory implements IBeanFactory
{
    private $beanDefHolder;

    private $beanBuilder;

    public function __construct (holders\BeanDefinitionsHolder $beanDefHolder)
    {
        $this->beanDefHolder = $beanDefHolder;
    }

    public function getBean ($beanId)
    {
        if (!$this->beanBuilder) {
            throw new \Exception("A bean builder must be set.");
        }
        // TODO - check beanId
        return $this->beanBuilder->build(
            $beanId,
            $this->beanDefHolder->getBeanDefinition($beanId)
        );
    }

    /**
     * @param IBeanBuilder $beanBuilder
     */
    public function setBeanBuilder (IBeanBuilder $beanBuilder)
    {
        $this->beanBuilder = $beanBuilder;
    }
}
