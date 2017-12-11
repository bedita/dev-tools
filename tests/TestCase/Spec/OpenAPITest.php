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
namespace BEdita\Core\Test\TestCase\Utility;

use BEdita\DevTools\Spec\OpenAPI;
use Cake\Core\Configure;
use Cake\TestSuite\TestCase;
use Symfony\Component\Yaml\Yaml;

/**
 * {@see \BEdita\DevTools\Spec\OpenAPI} Test Case
 *
 * @coversDefaultClass  \BEdita\DevTools\Spec\OpenAPI
 */
class OpenAPITest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.BEdita/Core.object_types',
        'plugin.BEdita/Core.property_types',
        'plugin.BEdita/Core.properties',
        'plugin.BEdita/Core.objects',
        'plugin.BEdita/Core.locations',
        'plugin.BEdita/Core.media',
        'plugin.BEdita/Core.profiles',
        'plugin.BEdita/Core.users',
        'plugin.BEdita/Core.relations',
        'plugin.BEdita/Core.roles',
        'plugin.BEdita/Core.relation_types',
        'plugin.BEdita/Core.streams',
    ];

    /**
     * Test `generate` method
     *
     * @return void
     *
     * @covers ::generate()
     * @covers ::dynamicPaths()
     * @covers ::dynamicSchemas()
     * @covers ::loadConfigurations()
     * @covers ::retrieveSchema()
     */
    public function testGenerate()
    {
        $result = OpenAPI::generate();

        static::assertNotEmpty($result);
        $expectedKeys = ['openapi', 'info', 'servers', 'paths', 'components'];

        static::assertEquals($expectedKeys, array_keys($result));
    }

    /**
     * Test `generateYaml` method
     *
     * @return void
     *
     * @covers ::generateYaml()
     */
    public function testGenerateYaml()
    {
        $result = OpenAPI::generateYaml();

        static::assertNotEmpty($result);

        $data = Yaml::parse($result);
        $expectedKeys = ['openapi', 'info', 'servers', 'paths', 'components'];
        static::assertEquals($expectedKeys, array_keys($data));
    }

    /**
     * Test `availableTypes` method
     *
     * @return void
     *
     * @covers ::availableTypes()
     * @covers ::clear()
     */
    public function testAvailableTypes()
    {
        OpenAPI::clear();
        $result = OpenAPI::availableTypes();
        static::assertNotEmpty($result);
    }
}
