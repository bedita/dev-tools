<?php
return [
    /**
     * OpenAPI `auth/` specification
     */
    'OpenAPI' => [
        'paths' => [
            '/auth' => [
                'post' => [
                    'description' => "Authentication process and token renewal.
                            You do auth with POST /auth, passing auth data in as formData parameters. For instance:

                            ```
                            username: johndoe
                            password: ******
                            ```

                            You renew token with POST /auth, using header parameter Authorization. For example:

                            ```
                            Authorization: 'Bearer eyJ0eXAiOi...2ljSerKQygk2T8'
                            ```",
                    'summary' => 'Perform auth or renew token',
                    'tags' => ['auth'],

                    'parameters' => [
                        [
                            'in' => 'header',
                            'name' => 'Authorization',
                            'description' => "Use token prefixed with 'Bearer'",
                            'required' => false,
                            'type' => 'string',
                        ],
                    ],

                    'requestBody' => [
                        'description' => 'Login with username/password or renew auth token',
                        'required' => false,
                        'content' => [
                            'application/json' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/auth_login'
                                ],
                            ],
                            'application/x-www-form-urlencoded' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/auth_login'
                                ],
                            ],
                        ],
                    ],
                    'responses' => [
                        '200' => [
                            'description' => 'Successful login',
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => '#/components/schemas/auth_success'
                                    ],
                                ],
                            ],
                        ],
                        '401' => [
                           '$ref' => '#/components/responses/401',
                        ],
                    ],
                ],

                'get' => [
                    'summary' => 'Get logged user profile data',
                    'tags' => ['auth'],
                    'responses' => [
                        '200' => [
                            'content' => [
                                'application/json' => [
                                    'schema' => [
                                        '$ref' => '#/components/schemas/users'
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

        // components used only in `auth/`
        'components' => [

            'schemas' => [

                'auth_login' => [
                    'type' => 'object',
                    'properties' => [
                        'username' => [
                            'type' => 'string',
                        ],
                        'password' => [
                            'type' => 'string',
                        ],
                    ],
                ],

                'auth_success' => [
                    'type' => 'object',
                    'properties' => [
                        'links' => [
                            '$ref' => '#/components/schemas/links',
                        ],
                        'meta' => [
                            'type' => 'object',
                            'properties' => [
                                'jwt' => [
                                    'type' => 'string',
                                ],
                                'renew' => [
                                    'type' => 'string',
                                ],
                            ],
                        ],
                    ],
                ],

            ],
        ]
    ],
];
