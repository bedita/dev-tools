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
use BEdita\DevTools\Spec\OpenAPI;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Event\Event;
use Cake\Routing\Router;
use Zend\Diactoros\Stream;

/**
 * Controller endpoint for `/tools` endpoint
 */
class ToolsController extends BaseController
{
    /**
     * YAML content type.
     *
     * @var string
     */
    const YAML_CONTENT_TYPE = 'application/x-yaml';

    /**
     * {@inheritDoc}
     *
     * @codeCoverageIgnore
     */
    public function initialize()
    {
        parent::initialize();

        $this->Auth->getAuthorize('BEdita/API.Endpoint')->setConfig('defaultAuthorized', true);

        if ($this->components()->has('JsonApi')) {
            $this->components()->unload('JsonApi');
        }
    }

    /**
     * {@inheritDoc}
     *
     * @codeCoverageIgnore
     */
    public function beforeFilter(Event $event)
    {
    }

    /**
     * Return OpenAPI spec in JSON or YAML
     *
     * @return \Cake\Http\Response
     */
    public function openApi()
    {
        $this->request->allowMethod('get');

        if ($this->request->is('json')) {
            $this->viewBuilder()->setClassName('Json');
            $this->set(OpenAPI::generate());
            $this->set('_serialize', true);

            return $this->render();
        }

        $this->set('yaml', OpenAPI::generateYaml());
        $this->viewBuilder()
            ->setPlugin('BEdita/DevTools')
            ->setLayout('open_api')
            ->setTemplatePath('OpenAPI')
            ->setTemplate('yaml')
            ->setClassName('View');

        if ($this->request->is('html')) {
            return $this->render();
        }

        return $this->render()->withType(static::YAML_CONTENT_TYPE);
    }
}
