<?php
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

use BEdita\DevTools\Middleware\HtmlMiddleware;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\Http\MiddlewareQueue;
use Cake\Http\ServerRequest;

/**
 * Configure DebugKit panels.
 */
$panels = ['BEdita/DevTools.Configuration'];
$panels = array_merge(Configure::read('DebugKit.panels') ?: [], $panels);
Configure::write('DebugKit.panels', $panels);

if (!Plugin::loaded('DebugKit')) {
    Plugin::load('DebugKit', ['bootstrap' => true, 'routes' => true]);
}

/**
 * Place HTML rendering middleware on top of middleware queue.
 */
ServerRequest::addDetector('html', ['accept' => ['text/html', 'application/xhtml+xml', 'application/xhtml', 'text/xhtml']]);
EventManager::instance()->on('Server.buildMiddleware', function (Event $event, MiddlewareQueue $middlewareQueue) {
    $middlewareQueue->insertAt(0, new HtmlMiddleware());
});
