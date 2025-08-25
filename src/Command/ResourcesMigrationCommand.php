<?php
declare(strict_types=1);

/**
 * BEdita, API-first content management framework
 * Copyright 2024 ChannelWeb Srl, Chialab Srl
 *
 * This file is part of BEdita: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * See LICENSE.LGPL or <http://gnu.org/licenses/lgpl-3.0.html> for more details.
 */
namespace BEdita\DevTools\Command;

use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Migrations\Command\BakeSimpleMigrationCommand;

/**
 * {@inheritDoc}
 *
 * Command class for generating resources migrations files.
 */
class ResourcesMigrationCommand extends BakeSimpleMigrationCommand
{
    /**
     * @inheritDoc
     */
    public function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->addArgument('name', [
            'help' => 'Name of the migration',
            'required' => true,
        ])
        ->addOption('force', [
            'short' => 'f',
            'boolean' => true,
            'default' => 'false',
            'help' => 'Force overwriting existing files without prompting.',
        ])
        ->addOption('connection', [
            'short' => 'c',
            'default' => 'default',
            'help' => 'The datasource connection to get data from.',
        ])
        ->addOption('source', [
            'short' => 's',
            'default' => 'Migrations',
            'help' => 'The migrations folder.',
        ]);

        return $parser;
    }

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
    public function bake(string $name, Arguments $args, ConsoleIo $io): void
    {
        // create .php file first, then .yml
        parent::bake($name, $args, $io);

        $contents = $this->createTemplateRenderer()
            ->set('name', $name)
            ->set($this->templateData($args))
            ->generate('BEdita/DevTools.yaml');

        $filename = $this->getPath($args) . str_replace('.php', '.yml', $this->fileName($name));
        $io->createFile($filename, $contents, $this->force);
    }
}
