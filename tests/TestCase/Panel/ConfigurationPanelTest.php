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

namespace BEdita\DebugKit\Test\TestCase\Panel;

use BEdita\DebugKit\Panel\ConfigurationPanel;
use Cake\Core\Configure;
use Cake\TestSuite\TestCase;

/**
 * Test configuration panel.
 */
class ConfigurationPanelTest extends TestCase
{

    /**
     * Debug Kit panel being tested.
     *
     * @var \DebugKit\DebugPanel|null
     */
    public $panel = null;

    /**
     * Set up test case.
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();

        $this->panel = new ConfigurationPanel();
        $this->panel->initialize();
    }

    /**
     * Check data being serialized in panel.
     *
     * @return void
     */
    public function testData()
    {
        $this->assertEquals(['content' => Configure::read()], $this->panel->data());
    }

    /**
     * Clean up after tests.
     *
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();
        unset($this->panel);
    }
}
