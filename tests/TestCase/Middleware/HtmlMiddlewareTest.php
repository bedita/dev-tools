<?php
declare(strict_types=1);

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

namespace BEdita\DevTools\Test\TestCase\Middleware;

use BEdita\DevTools\Middleware\HtmlMiddleware;
use Cake\Http\Response;
use Cake\Http\ServerRequest;
use Cake\TestSuite\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * {@see BEdita\DevTools\Middleware\HtmlMiddleware} Test Case
 */
#[CoversClass(HtmlMiddleware::class)]
class HtmlMiddlewareTest extends TestCase
{
    /**
     * Internal request handler test class.
     *
     * @param callable|null $callable Request handler callable.
     * @return \Psr\Http\Server\RequestHandlerInterface
     */
    public function requestHandlerClass(?callable $callable = null): RequestHandlerInterface
    {
        return new class ($callable) implements RequestHandlerInterface {
            /**
             * Test callable
             *
             * @var callable
             */
            public $callable;

            /**
             * Test reques
             *
             * @var \Psr\Http\Message\ServerRequestInterface
             */
            public $request;

            public function __construct(?callable $callable = null)
            {
                $this->callable = $callable ?: function ($request) {
                    return new Response();
                };
            }

            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                $this->request = $request;

                return ($this->callable)($request);
            }
        };
    }

    /**
     * Test execution with a non-HTML request.
     *
     * @return void
     */
    public function testJsonRequest()
    {
        $body = 'hello';

        $middleware = new HtmlMiddleware();

        $request = (new ServerRequest())->withHeader('Accept', 'application/json');
        $callable = function () use ($body) {
            return (new Response())->withStringBody($body);
        };
        $handler = $this->requestHandlerClass($callable);
        $result = $middleware->process($request, $handler);

        static::assertSame($body, (string)$result->getBody());
    }

    /**
     * Test execution with a non-JSON response.
     *
     * @return void
     */
    public function testInnerHtmlResponse()
    {
        $body = 'hello';

        $middleware = new HtmlMiddleware();

        $request = (new ServerRequest())->withHeader('Accept', 'text/html');
        $callable = function (ServerRequest $request) use ($body) {
            static::assertSame('application/vnd.api+json', $request->getHeaderLine('Accept'));

            return (new Response())
                ->withType('html')
                ->withStringBody($body);
        };
        $handler = $this->requestHandlerClass($callable);
        $result = $middleware->process($request, $handler);

        static::assertStringContainsString('text/html', $result->getHeaderLine('Content-Type'));
        static::assertSame($body, (string)$result->getBody());
    }

    /**
     * Test execution.
     *
     * @return void
     */
    public function testResponse()
    {
        /** @var string $body */
        $body = json_encode(['meta' => ['gustavo' => 'supporto']]);

        $middleware = new HtmlMiddleware();

        $request = (new ServerRequest())->withHeader('Accept', 'text/html');
        $callable = function (ServerRequest $request) use ($body) {
            static::assertSame('application/vnd.api+json', $request->getHeaderLine('Accept'));

            return (new Response())
                ->withType('jsonapi')
                ->withStringBody($body);
        };
        $handler = $this->requestHandlerClass($callable);
        $result = $middleware->process($request, $handler);

        static::assertStringContainsString('text/html', $result->getHeaderLine('Content-Type'));
        static::assertStringContainsString('<!DOCTYPE html>', (string)$result->getBody());
        static::assertStringContainsString($body, (string)$result->getBody());
    }
}
