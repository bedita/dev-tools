<?php
declare(strict_types=1);

/**
 * BEdita, API-first content management framework
 * Copyright 2021 ChannelWeb Srl, Chialab Srl
 *
 * This file is part of BEdita: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * See LICENSE.LGPL or <http://gnu.org/licenses/lgpl-3.0.html> for more details.
 */
namespace BEdita\Core\Test\TestCase\Command;

use BEdita\DevTools\Command\ChangeLogCommand;
use Cake\Console\TestSuite\ConsoleIntegrationTestTrait;
use Cake\Core\Configure;
use Cake\Http\Client\Adapter\Stream;
use Cake\Http\Client\Response;
use Cake\Routing\Router;
use Cake\TestSuite\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversMethod;

/**
 * {@see BEdita\DevTools\Command\ChangeLogCommand} Test Case
 */
#[CoversClass(ChangeLogCommand::class)]
#[CoversMethod(ChangeLogCommand::class, 'buildOptionParser')]
#[CoversMethod(ChangeLogCommand::class, 'classify')]
#[CoversMethod(ChangeLogCommand::class, 'createChangeLog')]
#[CoversMethod(ChangeLogCommand::class, 'execute')]
#[CoversMethod(ChangeLogCommand::class, 'fetchPrs')]
#[CoversMethod(ChangeLogCommand::class, 'filterItems')]
#[CoversMethod(ChangeLogCommand::class, 'initialize')]
#[CoversMethod(ChangeLogCommand::class, 'loglines')]
#[CoversMethod(ChangeLogCommand::class, 'saveChangeLog')]
class ChangeLogCommandTest extends TestCase
{
    use ConsoleIntegrationTestTrait;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        Router::reload();
    }

    /**
     * Test buildOptionParser method
     *
     * @return void
     */
    public function testBuildOptionParser()
    {
        $this->exec('change_log --help');
        $this->assertOutputContains('Read closed PRs');
        $this->assertOutputContains('Version to release');
    }

    /**
     * Test `execute` method
     *
     * @return void
     */
    public function testExecute(): void
    {
        $body = json_encode([
            'total_count' => 1,
            'items' => [
                [
                    'title' => 'A great PR',
                    'html_url' => 'https://github.com/bedita/bedita/pull/1111',
                    'number' => 1111,
                    'labels' => [
                        [
                            'name' => 'Topic - Core',
                        ],
                    ],
                    'milestone' => [
                        'html_url' => 'https:/github.com/bedita/bedita/milestone/25',
                        'number' => 25,
                        'title' => '4.4.0',
                    ],
                ],
                [
                    'title' => 'Wrong milestone',
                    'milestone' => [
                        'title' => '1.1.1',
                    ],
                ],
                [
                    'title' => 'Undefined',
                    'html_url' => 'https://github.com/bedita/bedita/pull/1111',
                    'number' => 1111,
                    'labels' => [
                        [
                            'name' => 'Undefined',
                        ],
                    ],
                    'milestone' => [
                        'html_url' => 'https:/github.com/bedita/bedita/milestone/25',
                        'number' => 25,
                        'title' => '4.4.0',
                    ],
                ],
            ],
        ]);
        $response = new Response([], (string)$body);

        $mock = $this->getMockBuilder(Stream::class)
            ->getMock();
        $mock->expects($this->once())
            ->method('send')
            ->willReturn([$response]);

        $current = (array)Configure::read('ChangeLog');
        Configure::write('ChangeLog', [
            'client' => ['adapter' => $mock, 'protocolVersion' => '2'],
        ]);

        $this->exec('change_log 2021-01-01 4.4.0');
        $this->assertExitSuccess();
        $this->assertOutputContains('Changelog created. Bye.');

        unlink('changelog-4.4.0.md');
        Configure::write('ChangeLog', $current);
    }
}
