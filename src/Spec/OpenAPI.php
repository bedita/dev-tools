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

namespace BEdita\DevTools\Spec;

use BEdita\Core\Model\Schema\JsonSchema;
use Cake\Core\Configure;
use Cake\Routing\Router;
use Cake\Utility\Hash;
use Symfony\Component\Yaml\Yaml;

/**
 * OpenAPI generation utility methods.
 *
 * @since 4.0.0
 */
class OpenAPI
{
    /**
     * Available resource and object types described by the spec
     *
     * @var array
     */
    protected static $types = [];

    /**
     * Generate OpenAPI v3 project as PHP array.
     *
     * @return array
     */
    public static function generate()
    {
        Configure::load('BEdita/DevTools.open_api');
        $spec = Configure::read('OpenAPI');

        $spec['info']['title'] = Configure::read('Project.name') . ' API';
        $spec['info']['version'] = Configure::read('BEdita.version');
        $spec['servers'][0]['url'] = Router::fullBaseUrl();

        // add dynamic paths and components for resources/objects
        $spec['paths'] = array_merge($spec['paths'], static::dynamicPaths());
        $spec['components']['schemas'] = array_merge($spec['components']['schemas'], static::dynamicSchemas());

        return $spec;
    }

    /**
     * Generate OpenAPI v3 project as YAML string.
     *
     * @return string OpenAPI spec in YAML format
     */
    public static function generateYaml()
    {
        $spec = static::generate();

        // Using 20 as 'level where you switch to inline YAML' just to put a `high` number
        // and 4 as indentation spaces
        return Yaml::dump($spec, 20, 4, Yaml::DUMP_EMPTY_ARRAY_AS_SEQUENCE);
    }

    /**
     * Add dynamic `paths` from template
     *
     * @return array OpenAPI dynamic paths
     */
    protected static function dynamicPaths()
    {
        $res = [];
        $paths = Configure::read('OATemplates.paths');
        $types = static::availableTypes();
        foreach ($types as $t) {
            foreach ($paths as $url => $data) {
                $path = str_replace('{$resource}', $t, $url);
                $data = json_decode(str_replace('{$resource}', $t, json_encode($data)), true);
                $res[$path] = $data;
            }
        }

        return $res;
    }

    /**
     * Available types
     *
     * @return array OpenAPI dynamic paths
     */
    protected static function availableTypes()
    {
        if (empty(static::$types)) {
            static::$types = ['roles', 'objects', 'users'];
        }

        return static::$types;
    }

    /**
     * Add dynamic `components.schemas` from template
     *
     * @return array OpenAPI dynamic schemas
     */
    protected static function dynamicSchemas()
    {
        $res = [];
        $schemas = Configure::read('OATemplates.components.schemas');
        $types = static::availableTypes();
        foreach ($types as $t) {
            $template = json_decode(str_replace('{$resource}', $t, json_encode($schemas['{$resource}'])), true);
            $res[$t] = $template;
            $schema = static::retrieveSchema($t);
            foreach (['attributes', 'meta', 'relationships'] as $item) {
                $res[sprintf('%s_%s', $t, $item)] = $schema[$item];
            }
        }

        return $res;
    }

    /**
     * Get type schema array: attributes, meta and relationships
     *
     * @param string $type Input resource or object type
     * @return array OpenAPI dynamic components
     */
    protected static function retrieveSchema($type)
    {
        $schema = JsonSchema::typeSchema($type);

        $attributes = $meta = ['type' => 'object', 'properties' => []];
        $relationships = [];

        foreach ($schema['properties'] as $name => $data) {
            if (!empty($data['isMeta'])) {
                unset($data['isMeta']);
                $meta['properties'][$name] = $data;
            } else {
                $attributes['properties'][$name] = $data;
            }
        }

        return compact('attributes', 'meta', 'relationships');
    }
}
