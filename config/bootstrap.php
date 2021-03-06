<?php
/**
 * BEdita, API-first content management framework
 * Copyright 2019 ChannelWeb Srl, Chialab Srl
 *
 * This file is part of BEdita: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * See LICENSE.LGPL or <http://gnu.org/licenses/lgpl-3.0.html> for more details.
 */

use BEdita\DevTools\Middleware\HtmlMiddleware;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\Http\MiddlewareQueue;
use Cake\Http\ServerRequest;
use Cake\Routing\Middleware\AssetMiddleware;

ServerRequest::addDetector('html', ['accept' => ['text/html', 'application/xhtml+xml', 'application/xhtml', 'text/xhtml']]);

/**
 * Configure DebugKit panels.
 */
$panels = ['BEdita/DevTools.Configuration'];
$panels = array_merge(Configure::read('DebugKit.panels') ?: [], $panels);
Configure::write('DebugKit.panels', $panels);

if (!Configure::read('Accept.html', false)) {
    return;
}

/**
 * Place HTML rendering middleware on top of middleware queue and retturn
 */
EventManager::instance()->on('Server.buildMiddleware', function (Event $event, MiddlewareQueue $middlewareQueue) {
        $middlewareQueue->prepend([new AssetMiddleware(), new HtmlMiddleware()]);
});
