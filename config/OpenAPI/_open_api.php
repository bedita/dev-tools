<?php
return [
    /**
     * OpenAPI spec template
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
                    'description' => 'Information on avilable endpoints and methods',
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
