<?php
/**
 * @author:     Andrea Panattoni <panattoni.andrea@gmail.com>
 * @license:    http://opensource.org/licenses/MIT
 */

return [
    'beans' => [
        'importedBean' => [
            '\laborra\ioc\fileimport\BasicClass',
            'properties' => [
                'value' => 84
            ],
        ],

        'basicBeanRef' => [
            '\laborra\ioc\fileimport\RefClass',
            'properties' => [
                'refBean' => '@basicBean'
            ],
        ],
    ],
];
