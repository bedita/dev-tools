<?php
declare(strict_types=1);

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
 *
 * @coversDefaultClass \BEdita\DevTools\Panel\ConfigurationPanel
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
    public function setUp(): void
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
    public function tearDown(): void
    {
        parent::tearDown();

        unset($this->panel);
    }

    /**
     * Check data being serialized in panel.
     *
     * @return void
     * @covers ::initialize()
     */
    public function testData(): void
    {
        static::assertEquals(['content' => Configure::read()], $this->panel->data());
    }
}
