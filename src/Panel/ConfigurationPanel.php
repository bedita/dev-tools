<?php
/**
 * BEdita, API-first content management framework
 * Copyright 2017-2021 ChannelWeb Srl, Chialab Srl
 *
 * This file is part of BEdita: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * See LICENSE.LGPL or <http://gnu.org/licenses/lgpl-3.0.html> for more details.
 */
namespace BEdita\DevTools\Panel;

use Cake\Core\Configure;
use DebugKit\DebugPanel;

/**
 * Panel to inspect current app configuration.
 */
class ConfigurationPanel extends DebugPanel
{
    /**
     * Plugin name.
     *
     * @var string
     */
    public $plugin = 'BEdita/DevTools';

    /**
     * Collect configuration data when panel is initialized.
     *
     * @return void
     */
    public function initialize()
    {
        $this->_data = ['content' => Configure::read()];
    }
}
