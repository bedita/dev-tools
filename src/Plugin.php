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

namespace BEdita\DevTools;

use BEdita\DevTools\Middleware\HtmlMiddleware;
use Cake\Core\BasePlugin;
use Cake\Core\Configure;
use Cake\Core\PluginApplicationInterface;
use Cake\Http\MiddlewareQueue;
use Cake\Http\ServerRequest;
use Cake\Routing\Middleware\AssetMiddleware;

/**
 * Plugin class.
 */
class Plugin extends BasePlugin
{
    /**
     * {@inheritDoc}
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        parent::bootstrap($app);

        ServerRequest::addDetector('html', ['accept' => ['text/html', 'application/xhtml+xml', 'application/xhtml', 'text/xhtml']]);

        // Configure DebugKit panels.
        $panels = ['BEdita/DevTools.Configuration'];
        $panels = array_merge((array)Configure::read('DebugKit.panels', []), $panels);
        Configure::write('DebugKit.panels', $panels);
    }

    /**
     * {@inheritDoc}
     */
    public function middleware($middleware): MiddlewareQueue
    {
        $middleware = parent::middleware($middleware);
        if (!Configure::read('Accept.html', false)) {
            return $middleware;
        }

        return $middleware->prepend([new AssetMiddleware(), new HtmlMiddleware()]);
    }
}
