<?php

namespace BEdita\DevTools\Test\TestApp;

use Cake\Http\BaseApplication;
use Cake\Http\MiddlewareQueue;

/**
 * Application setup class.
 *
 * This defines the bootstrapping logic and middleware layers you
 * want to use in your application.
 */
class Application extends BaseApplication
{
    /**
     * {@inheritDoc}
     */
    public function bootstrap(): void
    {
        $this->addPlugin('BEdita/DevTools', ['path' => dirname(dirname(__DIR__)) . DS]);
    }

    /**
     * {@inheritDoc}
     */
    public function middleware($middlewareQueue): MiddlewareQueue
    {
        return $middlewareQueue;
    }
}
