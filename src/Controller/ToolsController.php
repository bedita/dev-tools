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
namespace BEdita\DevTools\Controller;

use BEdita\API\Controller\AppController as BaseController;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Event\Event;
use Cake\Routing\Router;

/**
 * Controller endpoint for `/tools` endpoint
 */
class ToolsController extends BaseController
{

    /**
     * {@inheritDoc}
     *
     * @codeCoverageIgnore
     */
    public function initialize()
    {
        parent::initialize();

        $this->Auth->getAuthorize('BEdita/API.Endpoint')->setConfig('defaultAuthorized', true);
    }

    /**
     * {@inheritDoc}
     */
    public function beforeFilter(Event $event)
    {
    }

    /**
     * Give suggestions for localities.
     *
     * @return void
     */
    public function openApi()
    {
        $this->request->allowMethod('get');
        $this->prepareSpec();
    }

    /**
     * Prepare OpenAPI v3 Yaml specification file content.
     *
     * @return void
     */
    protected function prepareSpec()
    {
        $this->set('project', Configure::read('Project.name'));
        $this->set('url', Router::fullBaseUrl());

        $this->viewBuilder()
            ->setPlugin('BEdita/DevTools')
            ->setLayout('open_api')
            ->setTemplatePath('OpenAPI')
            ->setTemplate('yaml');
            //->setClassName('View');
    }
}
