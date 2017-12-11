<?php
return [
    /**
     * OpenAPI common reusable components
     */
    'OpenAPI' => [
        'components' => [
            'securitySchemes' => [
                'apikey' => [
                    'type' => 'apiKey',
                    'name' => 'server_token',
                    'in' => 'query',
                ],
            ],

            'parameters' => [
                'id' => [
                    'name' => 'id',
                    'in' => 'path',
                    'description' => 'numeric resource/object id',
                    'required' => true,
                    'schema' => [
                        'type' => 'integer',
                        'format' => 'int64',
                    ],
                ],
                'q' => [
                    'name' => 'q',
                    'in' => 'query',
                    'description' => 'text search query on objects and resources',
                    'required' => false,
                    'schema' => [
                        'type' => 'string',
                    ],
                ],
                'fields' => [
                    'name' => 'fields',
                    'in' => 'query',
                    'description' => 'filter response fields as comma separated values',
                    'required' => false,
                    'schema' => [
                        'type' => 'string',
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

                'links_list' => [
                    'type' => 'object',
                    'properties' => [
                        'self' => [
                            'type' => 'string',
                        ],
                        'home' => [
                            'type' => 'string',
                        ],
                        'first' => [
                            'type' => 'string',
                        ],
                        'last' => [
                            'type' => 'string',
                        ],
                        'prev' => [
                            'type' => 'string',
                        ],
                        'next' => [
                            'type' => 'string',
                        ],
                    ],
                ],

                'meta_list' => [
                    'type' => 'object',
                    'properties' => [
                        'pagination' => [
                            'type' => 'object',
                            'properties' => [
                                'count' => [
                                    'type' => 'integer',
                                ],
                                'page' => [
                                    'type' => 'integer',
                                ],
                                'page_count' => [
                                    'type' => 'integer',
                                ],
                                'page_items' => [
                                    'type' => 'integer',
                                ],
                                'page_size' => [
                                    'type' => 'integer',
                                ],
                            ],
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

                '404' => [
                    'description' => 'Object or resource not found',
                    'content' => [
                        'application/json' => [
                            'schema' => [
                                '$ref' => '#/components/schemas/error'
                            ],
                        ],
                    ],
                ],

                '400' => [
                    'description' => 'Bad request',
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
];
