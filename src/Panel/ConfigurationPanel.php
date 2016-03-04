<?php
/**
 * BEdita - a semantic content management framework
 * Copyright (C) 2008-2016  Chia Lab s.r.l., Channelweb s.r.l.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace BEdita\DebugKit\Panel;

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
    public $plugin = 'BEdita/DebugKit';

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
