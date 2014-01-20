<?php
/**
 * @author:     Andrea Panattoni <panattoni.andrea@gmail.com>
 * @license:    http://opensource.org/licenses/MIT
 */

return [
    'imports' => [
        __DIR__.'/config-import.php'
    ],

    'beans' => [
        'basicBean' => [
            '\laborra\ioc\fileimport\BasicClass',
            'properties' => [
                'value' => 42
            ],
        ],

        'beanWithImportedRef' => [
            '\laborra\ioc\fileimport\RefClass',
            'properties' => [
                'refBean' => '@importedBean'
            ],
        ],
    ],
];
