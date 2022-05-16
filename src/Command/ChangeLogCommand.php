<?php
declare(strict_types=1);

/**
 * BEdita, API-first content management framework
 * Copyright 2021 ChannelWeb Srl, Chialab Srl
 *
 * This file is part of BEdita: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * See LICENSE.LGPL or <http://gnu.org/licenses/lgpl-3.0.html> for more details.
 */

namespace BEdita\DevTools\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Core\Configure;
use Cake\Core\InstanceConfigTrait;
use Cake\Http\Client;
use Cake\Utility\Hash;

/**
 * Command to generate a changelog snippet to prepare a new relase.
 */
class ChangeLogCommand extends Command
{
    use InstanceConfigTrait;

    /**
     * Default configuration for command.
     *
     * @var array
     */
    protected $_defaultConfig = [
        // Classification filter on labels
        'filter' => [
            'integration' => [
                'Topic - Integration',
                'Topic - Tests',
            ],
            'api' => [
                'Topic - API',
            ],
            'core' => [
                'Topic - Core',
                'Topic - Database',
                'Topic - Authentication',
                'Topic - ORM',
            ],
        ],
        // Changelog sections
        'sections' => [
            'api' => 'API',
            'core' => 'Core',
            'integration' => 'Integration',
            'other' => 'Other',
        ],
        // Issues search url
        'url' => 'https://api.github.com/search/issues',
        // HTTP client configuration
        'client' => [],
    ];

    /**
     * @inheritDoc
     */
    protected function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->addArgument('from', [
                'help' => 'Read closed PRs from this date in "YYYY-MM-DD" format',
                'required' => true,
            ])
            ->addArgument('version', [
                'help' => 'Version to release',
                'required' => true,
            ]);

        return $parser;
    }

    /**
     * @inheritDoc
     */
    public function initialize(): void
    {
        $config = (array)Configure::read('ChangeLog');
        $this->setConfig($config);
    }

    /**
     * Read closed PRs from github and create changelog file.
     *
     * @param \Cake\Console\Arguments $args The command arguments.
     * @param \Cake\Console\ConsoleIo $io The console io
     * @return null|int The exit code or null for success
     */
    public function execute(Arguments $args, ConsoleIo $io): ?int
    {
        $from = $args->getArgument('from');
        $io->out("Reading closed PRs from from $from");

        $json = $this->fetchPrs((string)$from);
        $items = (array)Hash::get($json, 'items');
        $version = (string)$args->getArgument('version');
        $items = $this->filterItems($items, $version);
        $io->out(sprintf('Loaded %d prs', count($items)));

        $changeLog = $this->createChangeLog($items);
        $this->saveChangeLog($changeLog, $version);

        $io->out('Changelog created. Bye.');

        return null;
    }

    /**
     * Filter items by Milestone: major version of item mileston should match requested version (4 o 5 for instance).
     *
     * @param array $items Changelog items
     * @param string $version Release version
     * @return array
     */
    protected function filterItems(array $items, string $version): array
    {
        $major = substr($version, 0, (int)strpos($version, '.'));

        return array_filter(
            $items,
            function ($item) use ($major) {
                $milestone = (string)Hash::get($item, 'milestone.title');
                $milestone = str_replace('-', '.', $milestone);
                $v = substr($milestone, 0, (int)strpos($milestone, '.'));

                return $v == $major;
            }
        );
    }

    /**
     * Fetch pull requests from Github
     *
     * @param string $from From date.
     * @return array
     */
    protected function fetchPrs(string $from): array
    {
        $client = new Client((array)$this->getConfig('client'));
        $query = [
            'q' => sprintf('is:pr draft:false repo:bedita/bedita merged:>%s', $from),
            'sort' => '-closed',
            'per_page' => 100,
        ];
        $headers = ['Accept' => 'application/vnd.github.v3+json'];
        /** @var string $url */
        $url = $this->getConfig('url');
        $response = $client->get($url, $query, compact('headers'));

        return (array)$response->getJson();
    }

    /**
     * Create changelog array with classified pull requests
     *
     * @param array $items PR items
     * @return array
     */
    protected function createChangeLog(array $items): array
    {
        $res = [];
        foreach ($items as $item) {
            $labels = Hash::extract($item, 'labels.{n}.name');
            $type = $this->classify((array)$labels);
            $res[$type][] = $item;
        }

        return $res;
    }

    /**
     * Classify PR from its labels
     *
     * @param array $labels Labels array
     * @return string
     */
    protected function classify(array $labels): string
    {
        foreach ((array)$this->getConfig('filter') as $name => $data) {
            if (!empty(array_intersect($labels, (array)$data))) {
                return $name;
            }
        }

        return 'other';
    }

    /**
     * Save changelog to file
     *
     * @param array $changeLog Changelog items
     * @param string $version Version released
     * @return void
     */
    protected function saveChangeLog(array $changeLog, string $version): void
    {
        $out = sprintf("## Version %s - Cactus\n", $version);

        foreach ((array)$this->getConfig('sections') as $name => $label) {
            /** @var string $label */
            $out .= sprintf("\n### %s changes (%s)\n\n", $label, (string)$version);
            $out .= $this->loglines((array)Hash::get($changeLog, $name));
        }

        file_put_contents(sprintf('changelog-%s.md', $version), $out);
    }

    /**
     * Section changelog lines
     *
     * @param array $items Changelog items
     * @return string
     */
    protected function loglines(array $items): string
    {
        $res = '';
        foreach ($items as $item) {
            $id = (string)$item['number'];
            $url = (string)$item['html_url'];
            $title = (string)$item['title'];
            $res .= sprintf("* [#%s](%s) %s\n", $id, $url, $title);
        }

        return $res;
    }
}
