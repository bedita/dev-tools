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
        'components' => [
            'securitySchemes' => [
                'apikey' => [
                    'type' => 'apiKey',
                    'name' => 'server_token',
                    'in' => 'query',
                ],
            ],

            'parameters' => [
                'Id' => [
                    'name' => 'id',
                    'in' => 'path',
                    'description' => 'numeric resource/object id',
                    'required' => true,
                    'schema' => [
                        'type' => 'integer',
                        'format' => 'int64',
                    ],
                ],
            ],

            'schemas' => [

                'links' => [
                    'type' => 'object',
                    'properties' => [
                        'self' => [
                            'type' => 'string',
                        ],
                        'home' => [
                            'type' => 'string',
                        ],
                    ],
                ],

                'status' => [
                    'type' => 'object',
                    'properties' => [
                        'links' => [
                            '$ref' => '#/components/schemas/links',
                        ],
                        'meta' => [
                            'type' => 'object',
                            'properties' => [
                                'status' => [
                                    'type' => 'object',
                                    'properties' => [
                                        'environment' => [
                                            'type' => 'string',
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],

                'home' => [
                    'type' => 'object',
                    'properties' => [
                        'links' => [
                            '$ref' => '#/components/schemas/links',
                        ],
                        'meta' => [
                            'type' => 'object',
                            'properties' => [
                                'resources' => [
                                    'type' => 'array',
                                    'items' => [
                                        'type' => 'object',
                                    ]
                                ],
                                'project' => [
                                    'properties' => [
                                        'name' => [
                                            'type' => 'string',
                                        ],
                                    ],
                                ],
                                'version' => [
                                    'type' => 'string',
                                ],
                            ],
                        ],
                    ],
                ],

                'error' => [
                    'properties' => [
                        'error' => [
                            'properties' => [
                                'status' => [
                                    'type' => 'string',
                                ],
                                'title' => [
                                    'type' => 'string',
                                ],
                                'code' => [
                                    'type' => 'string',
                                ],
                                'description' => [
                                    'type' => 'string',
                                ],
                            ],
                        ],
                        'links' => [
                            '$ref' => '#/components/schemas/links',
                        ],
                    ],
                ],
            ],

            'responses' => [
                '401' => [
                    'description' => 'Authorization information is missing or invalid',
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/error'
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],

    'OATemplates' => [
        'paths' => [
            '/{$resource}/{id}' => [
                'get' => [
                    'summary' => '',
                    'description' => '',
                    'parameters' => [
                        [
                            '$ref' => '#/components/parameters/Id',
                        ],
                    ],
                    'responses' => [
                        '200' => [
                            'description' => 'Retrieve single "{$resource}"',
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => '#/components/schemas/{$resource}'
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
        ],
        'components' => [
            'schemas' => [
                '{$resource}' => [
                    'type' => 'object',
                    'properties' => [
                        'data' => [
                            'type' => 'object',
                            'properties' => [
                                'id' => [
                                    'type' => 'string',
                                ],
                                'type' => [
                                    'type' => 'string',
                                ],
                                'attributes' => [
                                    'type' => 'object',
                                    'properties' => [
                                        '$ref' => '#/components/schemas/{$resource}_attributes',
                                    ],
                                ],
                                'meta' => [
                                    'type' => 'object',
                                    'properties' => [
                                        '$ref' => '#/components/schemas/{$resource}_meta',
                                    ],
                                ],
                                'relationships' => [
                                    'type' => 'object',
                                    'properties' => [
                                        '$ref' => '#/components/schemas/{$resource}_relationships',
                                    ],
                                ],
                            ]
                        ],
                        'links' => [
                            '$ref' => '#/components/schemas/links',
                        ],
                    ]
                ],
            ],
        ],
    ],

];
