<?php

return [

    'context' => [
        'lazyLoading' => true, // Default lazy loading beans
        'baseNamespace' => 'laborra\ioc\full', // Relative class reference shortcut TODO - Check PSR
    ],

    'parameters' => [
        'parameterName1' => 'parameterValue1',
    ],

    'beans' => [
        'basicBean' => [
            'class' => '\laborra\ioc\full\BasicClass',
        ],

        'shortcutBean' => [
            '\laborra\ioc\full\BasicClass', // If the first element has a numeric key, then it is the class name
        ],

        'constructorArgBean' => [
            '#\ConstructorArgClass',
            'constructorArgs' => [
                'simple value', // Any php expression that resolve to a value
                '%parameterName1%', // It will be 'paramterValue1'
                '@basicBean', // It will be a reference to the bean with id 'basicBean'
            ],
        ],

        'setterBean' => [
            '#\SetterClass',
            'properties' => [
                'value' => 'simpleValue',
                'param' => '%parameterName1%',
                'reference' => '@basicBean',
            ]
        ],

        'callsBean' => [
            '#\CallClass',
            'calls' => [
                'noArgsCall',
                'argsCall' => ['foo', '@setterBean'],
            ],
        ],
    ],

    'aspects' => [
        // Not yet implemented
    ],
];



