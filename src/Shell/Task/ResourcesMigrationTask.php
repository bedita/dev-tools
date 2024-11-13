<?php
declare(strict_types=1);

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

use Bake\Utility\TemplateRenderer;
use Migrations\Shell\Task\SimpleMigrationTask;

/**
 * {@inheritDoc}
 *
 * Task class for generating resources migrations files.
 */
class ResourcesMigrationTask extends SimpleMigrationTask
{
    /**
     * Main migration file name.
     *
     * @var string|null
     */
    protected ?string $migrationFile = null;

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'resources_migration';
    }

    /**
     * @inheritDoc
     */
    public function fileName($name): string
    {
        if (isset($this->migrationFile)) {
            return $this->migrationFile;
        }

        return $this->migrationFile = parent::fileName($name);
    }

    /**
     * @inheritDoc
     */
    public function template(): string
    {
        return 'BEdita/DevTools.resources';
    }

    /**
     * @inheritDoc
     */
    public function bake($name): string
    {
        // create .php file first, then .yml
        parent::bake($name);

        $renderer = new TemplateRenderer($this->theme);
        $renderer->set('name', $name);
        $renderer->set($this->templateData());
        $contents = $renderer->generate('BEdita/DevTools.yaml');

        $filename = $this->getPath() . str_replace('.php', '.yml', $this->fileName($name));
        $this->createFile($filename, $contents);

        return $contents;
    }
}
