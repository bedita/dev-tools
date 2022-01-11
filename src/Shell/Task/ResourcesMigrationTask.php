<?php
/**
 * BEdita, API-first content management framework
 * Copyright 2017-2022 ChannelWeb Srl, Chialab Srl
 *
 * This file is part of BEdita: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * See LICENSE.LGPL or <http://gnu.org/licenses/lgpl-3.0.html> for more details.
 */
namespace BEdita\DevTools\Shell\Task;

use Cake\Utility\Inflector;
use Migrations\Shell\Task\SimpleMigrationTask;
use Phinx\Util\Util;

/**
 * Task class for generating resources migrations files.
 *
 * {@inheritDoc}
 */
class ResourcesMigrationTask extends SimpleMigrationTask
{
    /**
     * Main migration file name.
     *
     * @var string
     */
    protected $migrationFile = '';

    /**
     * {@inheritDoc}
     */
    public function name()
    {
        return 'resources_migration';
    }

    /**
     * {@inheritDoc}
     */
    public function fileName($name)
    {
        $name = $this->getMigrationName($name);
        $this->migrationFile = Util::getCurrentTimestamp() . '_' . Inflector::camelize($name) . '.php';

        return $this->migrationFile;
    }

    /**
     * {@inheritDoc}
     */
    public function template()
    {
        return 'BEdita/DevTools.resources';
    }

    /**
     * {@inheritDoc}
     */
    public function bake($name)
    {
        // create .php file first, then .yml
        parent::bake($name);

        $contents = $this->BakeTemplate->generate('BEdita/DevTools.yaml');

        $filename = $this->getPath() . str_replace('.php', '.yml', $this->migrationFile);
        $this->createFile($filename, $contents);

        return $contents;
    }
}
