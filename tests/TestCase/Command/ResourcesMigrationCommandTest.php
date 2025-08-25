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
namespace BEdita\DevTools\Test\TestCase\Shell\Task;

use BEdita\DevTools\Command\ResourcesMigrationCommand;
use Cake\Console\Arguments;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Cake\Core\Plugin;
use Cake\Routing\Router;
use Cake\TestSuite\StringCompareTrait;
use Cake\TestSuite\TestCase;

/**
 * Test resources migration task.
 *
 * @coversDefaultClass \BEdita\DevTools\Command\ResourcesMigrationCommand
 */
class ResourcesMigrationCommandTest extends TestCase
{
    use ConsoleIntegrationTestTrait;
    use StringCompareTrait;

    /**
     * Keep trace of created files to cleanup at the end of tests.
     *
     * @var string[]
     */
    protected $createdFiles = [];

    /**
     * @inheritDoc
     */
    public function setUp(): void
    {
        parent::setUp();
        Router::reload();
        $this->loadPlugins(['Bake']);
        $this->setAppNamespace('BEdita\DevTools\Test\TestApp');
        $this->_compareBasePath = Plugin::path('BEdita/DevTools') . 'tests' . DS . 'comparisons' . DS . 'Migrations' . DS;
    }

    /**
     * @inheritDoc
     */
    public function tearDown(): void
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
     * @covers ::name()
     */
    public function testName(): void
    {
        $command = new ResourcesMigrationCommand();
        $expected = 'resources_migration';
        $actual = $command->name();
        static::assertEquals($actual, $expected);
    }

    /**
     * Test `fileName`.
     *
     * @return void
     * @covers ::fileName()
     */
    public function testFileName(): void
    {
        $command = new class () extends ResourcesMigrationCommand
        {
            public function setArgs(Arguments $args): void
            {
                $this->args = $args;
            }
        };
        $command->setArgs(new Arguments(['name' => 'MyMigration'], [], []));
        $expected = $command->fileName('MyMigration');
        sleep(2);
        $actual = $command->fileName('MyMigration');
        static::assertEquals($expected, $actual, 'Migration file name is not preserved');
    }

    /**
     * Test `template`.
     *
     * @return void
     * @covers ::template()
     */
    public function testTemplate(): void
    {
        $command = new ResourcesMigrationCommand();
        $expected = 'BEdita/DevTools.resources';
        $actual = $command->template();
        static::assertEquals($actual, $expected);
    }

    /**
     * Test `bake`.
     *
     * @return void
     * @covers ::bake()
     */
    public function testBake(): void
    {
        $this->exec('bake resources_migration MyMigration');

        $this->assertExitCode(ResourcesMigrationCommand::CODE_SUCCESS);
        $basePath = CONFIG . ResourcesMigrationCommand::DEFAULT_MIGRATION_FOLDER . DS;

        $file = glob($basePath . '*_MyMigration.php');
        // @phpstan-ignore-next-line
        $phpFile = current($file);
        $file = glob($basePath . '*_MyMigration.yml');
        // @phpstan-ignore-next-line
        $yamlFile = current($file);

        $phpResult = file_get_contents($phpFile);
        $yamlResult = file_get_contents($yamlFile);

        $this->createdFiles[] = $phpFile;
        $this->createdFiles[] = $yamlFile;

        $this->assertSameAsFile('testMyMigration.php', (string)$phpResult);
        $this->assertSameAsFile('testMyMigration.yml', (string)$yamlResult);
    }
}
