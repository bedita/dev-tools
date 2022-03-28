<?php
/**
 * BEdita, API-first content management framework
 * Copyright 2020 ChannelWeb Srl, Chialab Srl
 *
 * This file is part of BEdita: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * See LICENSE.LGPL or <http://gnu.org/licenses/lgpl-3.0.html> for more details.
 */

namespace BEdita\DevTools\Test\TestCase\Shell\Task;

use Bake\Shell\Task\BakeTemplateTask;
use BEdita\DevTools\Shell\Task\ResourcesMigrationTask;
use Cake\TestSuite\TestCase;

/**
 * Test resources migration task.
 *
 * @coversDefaultClass \BEdita\DevTools\Shell\Task\ResourcesMigrationTask
 */
class ResourcesMigrationTaskTest extends TestCase
{
    /**
     * Keep trace of created files to cleanup at the end of tests.
     *
     * @var string[]
     */
    protected $createdFiles = [];

    /**
     * {@inheritDoc}
     */
    public function tearDown()
    {
        parent::tearDown();

        foreach ($this->createdFiles as $file) {
            unlink($file);
        }
    }

    /**
     * Test `name`.
     *
     * @return void
     *
     * @covers ::name()
     */
    public function testName(): void
    {
        $task = new ResourcesMigrationTask();
        $expected = 'resources_migration';
        $actual = $task->name();
        static::assertEquals($actual, $expected);
    }

    /**
     * Test `fileName`.
     *
     * @return void
     *
     * @covers ::fileName()
     */
    public function testFileName(): void
    {
        $task = new ResourcesMigrationTask();
        $expected = $task->fileName('MyMigration');
        sleep(2);
        $actual = $task->fileName('MyMigration');
        static::assertEquals($expected, $actual, 'Migration file name is not preserved');
    }

    /**
     * Test `template`.
     *
     * @return void
     *
     * @covers ::template()
     */
    public function testTemplate(): void
    {
        $task = new ResourcesMigrationTask();
        $expected = 'BEdita/DevTools.resources';
        $actual = $task->template();
        static::assertEquals($actual, $expected);
    }

    /**
     * Test `bake`.
     *
     * @return void
     *
     * @covers ::bake()
     */
    public function testBake(): void
    {
        $task = new ResourcesMigrationTask();
        $task->BakeTemplate = new BakeTemplateTask();
        $actual = $task->bake('MyMigration');
        $expected = $task->BakeTemplate->generate('BEdita/DevTools.yaml');
        static::assertEquals($actual, $expected);

        // verify file php exists
        $filename = $task->getPath() . $task->fileName('MyMigration');
        static::assertFileExists($filename);
        $this->createdFiles[] = $filename;

        // verify file yml exists
        $filename = $task->getPath() . str_replace('.php', '.yml', $task->fileName('MyMigration'));
        static::assertFileExists($filename);
        $this->createdFiles[] = $filename;
    }
}
