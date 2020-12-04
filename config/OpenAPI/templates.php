<?php
return [
    /**
     * OpenAPI templates
     *
     * Each item in `paths` and `components` contains a `{$resource}` placeholder
     * that will be replaced with actual resource and object types names
     *
     * Note:
     *  - `{$resource}_attributes`, `{$resource}_meta` and `{$resource}_relationships` will be dynamically created
     *
     */
    'OATemplates' => [
        'paths' => [
            '/{$resource}/{id}' => [
                'get' => [
                    'summary' => 'Retrieve single "{$resource}"',
                    'description' => '',
                    'parameters' => [
                        [
                            '$ref' => '#/components/parameters/id',
                        ],
                    ],
                    'tags' => ['objects'],
                    'responses' => [
                        '200' => [
                            'description' => '',
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
                        '404' => [
                            '$ref' => '#/components/responses/404',
                        ],
                    ],
                ],
            ],

            '/{$resource}' => [
                'get' => [
                    'summary' => 'Retrieve list of "{$resource}" with optional search, fields and custom filters',
                    'description' => '',
                    'parameters' => [
                        [
                            '$ref' => '#/components/parameters/q',
                        ],
                        [
                            '$ref' => '#/components/parameters/fields',
                        ],
                    ],
                    'tags' => ['objects'],
                    'responses' => [
                        '200' => [
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => '#/components/schemas/{$resource}_list'
                                    ],
                                ]
                            ],
                        ],
                        '401' => [
                            '$ref' => '#/components/responses/401',
                        ],
                        '404' => [
                            '$ref' => '#/components/responses/404',
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
                    ],
                ],

                '{$resource}_list' => [
                    'type' => 'object',
                    'properties' => [
                        'data' => [
                            'type' => 'array',
                            'items' => [
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
                                ],
                            ],
                        ],
                        'links' => [
                            '$ref' => '#/components/schemas/links_list',
                        ],
                        'meta' => [
                            '$ref' => '#/components/schemas/meta_list',
                        ],
                    ],
                ],

            ],
        ],
    ],
];
