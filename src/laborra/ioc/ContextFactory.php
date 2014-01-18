<?php

namespace laborra\ioc;

use laborra\ioc\beanbuilders\BeanBuilder;
use laborra\ioc\beanbuilders\BeanBuilderProxy;
use laborra\ioc\beanbuilders\DefaultReplacer;
use laborra\ioc\beanbuilders\IBeanBuilder;
use laborra\ioc\beanbuilders\ParamResolver;
use laborra\ioc\beanbuilders\ReferenceReplacer;
use laborra\ioc\beanbuilders\SyntaxChecker;
use laborra\ioc\holders\ParametersHolder;

class ContextFactory
{
    public static function buildFromFile ($fileName)
    {
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        if ($extension == 'php') {
            return self::buildFromPHPArray(include($fileName));
        }

        if ($extension == 'yml') {
            return self::buildFromYamlFile($fileName);
        }

        throw new BadConfigurationException("File $fileName not supported.");
    }

    public static function buildFromPHPArray ($config)
    {
        if (!isset($config['parameters'])) {
            $config['parameters'] = [];
        }

        if (!isset($config['beans'])) {
            $config['beans'] = [];
        }

        if (!isset($config['context'])) {
            $config['context'] = [];
        }

        $paramsHolder = new ParametersHolder($config['parameters']);
        $beanDefHolder = new holders\BeanDefinitionsHolder($config['beans']);
        $contextOptions = new holders\ContextOptionsHolder($config['context']);

        $beanFactory = new BeanFactory($beanDefHolder);

        $baseBuilder = new BeanBuilder();

        if ($contextOptions->getBaseNameSpace()) {
            $baseBuilder = new beanbuilders\BaseNameSpaceResolver(
                $baseBuilder,
                $contextOptions->getBaseNameSpace()
            );
        }

        $builder = new beanbuilders\SingletonBuilder(
            new DefaultReplacer(
                new SyntaxChecker(
                    new ParamResolver(
                        new ReferenceReplacer(
                            $baseBuilder,
                            $beanFactory
                        ),
                        $paramsHolder
                    )
                )
            )
        );

        $beanFactory->setBeanBuilder($builder);

        $appContext = new AppContext(
            $paramsHolder,
            $beanFactory
        );

        return $appContext;
    }

    public static function buildFromYamlFile ($fileName)
    {
        // TODO
        throw new \Exception("Not supported yet");
    }
}
