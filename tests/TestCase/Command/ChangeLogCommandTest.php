<?php
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

use Cake\Core\Configure;
use Cake\Http\Client\Adapter\Stream;
use Cake\Http\Client\Response;
use Cake\Routing\Router;
use Cake\TestSuite\ConsoleIntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * {@see BEdita\DevTools\Command\ChangeLogCommand} Test Case
 *
 * @coversDefaultClass \BEdita\DevTools\Command\ChangeLogCommand
 */
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
        $this->useCommandRunner();
        Router::reload();
    }

    /**
     * Test buildOptionParser method
     *
     * @return void
     *
     * @covers ::buildOptionParser()
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
     *
     * @covers ::execute()
     * @covers ::fetchPrs()
     * @covers ::classify()
     * @covers ::saveChangeLog()
     * @covers ::loglines()
     */
    public function testExecute(): void
    {
        $response = new Response();
        $mock = $this->getMockBuilder(Stream::class)
            ->getMock();
        $mock->expects($this->once())
            ->method('send')
            ->will($this->returnValue([$response]));

        $current = (array)Configure::read('ChangeLog');
        Configure::write('ChangeLog', [
            'client' => ['adapter' => $mock, 'protocolVersion' => '2'],
        ]);

        $this->exec('change_log 2021-01-01 4.4.0');
        $this->assertExitSuccess();
        $this->assertOutputContains('Changelog created. Bye.');

        Configure::write('ChangeLog', $current);
    }
}
