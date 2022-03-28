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

namespace BEdita\DevTools\Test\TestCase\Panel;

use BEdita\DevTools\Panel\ConfigurationPanel;
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
     * @var \DebugKit\DebugPanel
     */
    public $panel;

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
     * Clean up after tests.
     *
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();

        unset($this->panel);
    }

    /**
     * Check data being serialized in panel.
     *
     * @return void
     */
    public function testData()
    {
        static::assertEquals(['content' => Configure::read()], $this->panel->data());
    }
}
