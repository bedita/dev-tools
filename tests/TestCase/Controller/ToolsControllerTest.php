<?php
/**
 * BEdita, API-first content management framework
 * Copyright 2017 ChannelWeb Srl, Chialab Srl
 *
 * This file is part of BEdita: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * See LICENSE.LGPL or <http://gnu.org/licenses/lgpl-3.0.html> for more details.
 */

namespace BEdita\DevTools\Test\TestCase\Controller;

use BEdita\API\TestSuite\IntegrationTestCase;

/**
 * {@see \BEdita\DevTools\Controller\ToolsController} Test Case
 *
 * @coversDefaultClass \BEdita\DevTools\Controller\ToolsController
 */
class ToolsControllerTest extends IntegrationTestCase
{
    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.BEdita/Core.media',
        'plugin.BEdita/Core.locations',
        'plugin.BEdita/Core.property_types',
        'plugin.BEdita/Core.properties',
    ];

    /**
     * Data provider for `testOpenAPI`
     *
     * @return array
     */
    public function openAPIProvider()
    {
        return [
            'json' => [
                'application/json',
                'application/json',
            ],
            'browser' => [
                'text/html',
                'text/html',
            ],
            'yaml' => [
                'application/x-yaml',
                '*/*'
            ],
        ];
    }

    /**
     * Test `openApi` method.
     *
     * @param string $expected Expected response content type
     * @param string $accept Accept request header
     * @return void
     *
     * @covers ::openApi()
     * @covers ::initialize()
     * @dataProvider openAPIProvider
     */
    public function testOpenAPI($expected, $accept = '')
    {
        $headers = empty($accept) ? [] : ['Accept' => $accept];
        $this->configRequestHeaders('GET', $headers);
        $this->get('/tools/open-api');

        $body = (string)$this->_response->getBody();
        static::assertNotEmpty($body);

        $this->assertResponseCode(200);
        $this->assertContentType($expected);
    }
}
