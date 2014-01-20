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
use Symfony\Component\Yaml\Yaml;

class ContextFactory
{
    public static function buildFromFile ($fileName)
    {
        return self::buildFromPHPArray(
            self::importFile($fileName)
        );
    }

    private static function importPHPFile ($fileName)
    {
        return include($fileName);
    }

    private static function importYAMLFile ($fileName)
    {
        $config = Yaml::parse($fileName);

        if (isset($config['imports'])) {
            foreach ($config['imports'] as $index => $import) {
                if (substr($import, 0, 2) == './') {
                    $config['imports'][$index] = dirname($fileName).DIRECTORY_SEPARATOR.$import;
                }
            }
        }
        return $config;
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

        if (isset($config['imports'])) {
            foreach ($config['imports'] as $import) {
                $config = array_merge_recursive(
                    $config,
                    self::importFile($import)
                );
            }
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
        return self::buildFromPHPArray(self::importYAMLFile($fileName));
    }

    /**
     * @param $fileName
     * @throws BadConfigurationException
     * @return array
     */
    public static function importFile ($fileName)
    {
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);
        switch ($extension) {
            case 'php':
                return self::importPHPFile($fileName);
                break;

            case 'yml':
                return self::importYAMLFile($fileName);
                break;

            default:
                throw new BadConfigurationException("File $fileName not supported.");
        }
    }
}
