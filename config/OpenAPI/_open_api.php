<?php
return [
    /**
     * OpenAPI main file.
     *
     * First configuration file loaded, contains spec main file structure in terms of 'openapi', 'info', 'servers',
     * 'paths' and 'components'
     *  - some values will be changed dynamically, e.g. 'info.title'
     *  - keys like 'paths' and 'components' will be merged with arrays in other configuration files or dynamically created
     *
     * Other noteworthy configuration files:
     *  - `{name}_endpoint.php` - 'paths' and 'components' for a specific endpoint
     *  - `components.php` - common 'components'
     *  - `templates.php` - templates in `OATemplates` will be rendered usign actual resources and object types
     *
     */
    'OpenAPI' => [
        'openapi' => '3.0.0',
        'info' => [
            'title' => '', // will be 'project name'
            'description' => 'Auto-generated specification',
            'version' => '', // will be 'BEdita version'
        ],
        'servers' => [
            [
                'url' => '', // will be 'project base URL'
            ],
        ],
        'paths' => [
            '/status' => [
                'get' => [
                    'summary' => 'API Status',
                    'description' => 'Service status response',
                    'tags' => ['status'],
                    'responses' => [
                        '200' => [
                            'description' => '',
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => '#/components/schemas/status'
                                    ],
                                ]
                            ],
                        ],
                        '401' => [
                            '$ref' => '#/components/responses/401',
                        ],
                    ],
                ],
            ],
            '/home' => [
                'get' => [
                    'summary' => 'API Home page',
                    'description' => 'Information on available endpoints and methods',
                    'tags' => ['home'],
                    'responses' => [
                        '200' => [
                            'description' => '',
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => '#/components/schemas/home'
                                    ],
                                ],
                            ],
                        ],
                        '401' => [
                           '$ref' => '#/components/responses/401',
                        ],
                    ],
                ],
            ],
        ],
        'components' => [],
    ],
];
