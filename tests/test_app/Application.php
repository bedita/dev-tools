<?php

namespace TestApp;

use Cake\Http\BaseApplication;

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
    public function bootstrap()
    {
        // Call parent to load bootstrap from files.
        parent::bootstrap();

        // Load DevTools plugin
        $this->addPlugin('BEdita/DevTools', ['bootstrap' => true, 'routes' => true, 'path' => dirname(dirname(__DIR__)) . DS]);
    }

    /**
     * {@inheritDoc}
     */
    public function middleware($middlewareQueue)
    {
        return $middlewareQueue;
    }
}
