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
namespace BEdita\DevTools\Middleware;

use Cake\Http\ServerRequest;
use Cake\View\ViewVarsTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * Intercept HTML requests and render an user-friendly view.
 *
 * @since 4.0.0
 */
class HtmlMiddleware implements MiddlewareInterface
{
    use ViewVarsTrait;

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!($request instanceof ServerRequest) || !$request->is('html')) {
            // Not an HTML request, or unable to detect easily.
            return $handler->handle($request);
        }

        // Set correct "Accept" header, and proceed as usual.
        $request = $request->withHeader('Accept', 'application/vnd.api+json');

        /** @var \Cake\Http\Response $response */
        $response = $handler->handle($request);

        if (!in_array($response->getHeaderLine('Content-Type'), ['application/json', 'application/vnd.api+json'])) {
            // Not a JSON response.
            return $response;
        }

        // Prepare HTML rendering.
        $this->viewBuilder()
            ->setPlugin('BEdita/DevTools')
            ->setLayout('html')
            ->setTemplatePath('Common')
            ->setTemplate('html');
        $this->set(compact('request', 'response'));
        $view = $this->createView();

        return $response
            ->withType('html')
            ->withStringBody($view->render());
    }
}
