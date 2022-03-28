<?php
/**
 * BEdita, API-first content management framework
 * Copyright 2022 Atlas Srl, Chialab Srl
 *
 * This file is part of BEdita: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * See LICENSE.LGPL or <http://gnu.org/licenses/lgpl-3.0.html> for more details.
 */

namespace BEdita\DevTools\Test\TestCase;

use BEdita\DevTools\Middleware\HtmlMiddleware;
use BEdita\DevTools\Plugin;
use Cake\Core\Configure;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Http\MiddlewareQueue;
use Cake\Http\ServerRequest;
use Cake\Routing\Middleware\AssetMiddleware;
use PHPUnit\Framework\TestCase;

/**
 * Test {@see \BEdita\DevTools\Plugin}.
 *
 * @coversDefaultClass \BEdita\DevTools\Plugin
 */
class PluginTest extends TestCase
{
    /**
     * Test subject.
     *
     * @var \BEdita\DevTools\Plugin
     */
    protected $plugin;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->plugin = new Plugin();
    }

    /**
     * Test {@see Plugin::bootstrap()} method.
     *
     * @return void
     *
     * @covers ::bootstrap()
     */
    public function testBootstrap(): void
    {
        $app = new class (CONFIG) extends BaseApplication {
            public function middleware($middleware)
            {
                return $middleware;
            }
        };

        Configure::write('DebugKit.panels', ['Foo', 'Bar']);
        $this->plugin->bootstrap($app);

        $request = new ServerRequest(['environment' => ['HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8']]);
        static::assertTrue($request->is('html'));

        $request = new ServerRequest(['environment' => ['HTTP_ACCEPT' => 'image/webp,image/apng,*/*;q=0.8']]);
        static::assertFalse($request->is('html'));

        static::assertSame(['Foo', 'Bar', 'BEdita/DevTools.Configuration'], Configure::read('DebugKit.panels'));
    }

    /**
     * Data provider for {@see PluginTest::testMiddleware()} test case.
     *
     * @return array[]
     */
    public function middlewareProvider(): array
    {
        return [
            'false' => [
                [ErrorHandlerMiddleware::class],
                false,
            ],
            'true' => [
                [AssetMiddleware::class, HtmlMiddleware::class, ErrorHandlerMiddleware::class],
                true,
            ],
            'null' => [
                [ErrorHandlerMiddleware::class],
                null,
            ],
        ];
    }

    /**
     * Test {@see Plugin::middleware()} method.
     *
     * @param string[] $expected Expected middleware queue.
     * @param bool|null $acceptHtml Value of `Accept.html` configuration.
     * @return void
     *
     * @dataProvider middlewareProvider()
     * @covers ::middleware()
     */
    public function testMiddleware(array $expected, ?bool $acceptHtml): void
    {
        $queue = new MiddlewareQueue();
        $queue = $queue->add(new ErrorHandlerMiddleware());
        Configure::write('Accept.html', $acceptHtml);

        $actual = $this->plugin->middleware($queue);

        static::assertSameSize($expected, $actual);
        foreach ($expected as $idx => $class) {
            static::assertInstanceOf($class, $actual->get($idx));
        }
    }
}
