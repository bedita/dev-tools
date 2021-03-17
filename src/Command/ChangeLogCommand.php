<?php
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

use Cake\Console\Arguments;
use Cake\Console\Command;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
use Cake\Http\Client;
use Cake\Utility\Hash;

class ChangeLogCommand extends Command
{
    /**
     * Classification filter on labels
     *
     * @var array
     */
    protected $filter = [
        'integration' => [
            'Topic - Integration',
            'Topic - Tests'
        ],
        'api' => [
            'Topic - API'
        ],
        'core' => [
            'Topic - Core',
            'Topic - Database',
            'Topic - Authentication',
            'Topic - ORM'
        ],
    ];

    /**
     * File sections
     *
     * @var array
     */
    protected $sections = [
        'api' => 'API',
        'core' => 'Core',
        'integration' => 'Integration',
        'other' => 'Other',
    ];

    /**
     * {@inheritDoc}
     */
    protected function buildOptionParser(ConsoleOptionParser $parser)
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

        $json = $this->fetchPrs($from);
        $items = (array)Hash::get($json, 'items');
        $io->out(sprintf('Loaded %d prs', count($items)));

        $changeLog = $this->createChangeLog($items);
        $this->saveChangeLog($changeLog, $args->getArgument('version'));

        $io->out('Changelog created. Bye.');

        return null;
    }

    /**
     * Fetch pull requests from Github
     *
     * @param string $from From date.
     * @return array
     */
    protected function fetchPrs(string $from): array
    {
        $url = 'https://api.github.com/search/issues';
        $client = new Client();
        $query = [
            'q' => sprintf('is:pr draft:false repo:bedita/bedita merged:>%s', $from),
            'sort' => '-closed',
            'per_page' => 100,
        ];
        $headers = ['Accept' => 'application/vnd.github.v3+json'];
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
            $milestone = (string)Hash::get($item, 'milestone.title');
            if (substr($milestone, 0, 1) !== '4') {
                continue;
            }
            $labels = Hash::extract($item, 'labels.{n}.name');
            $type = $this->classify($labels);
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
        foreach ($this->filter as $name => $data) {
            if (!empty(array_intersect($labels, $data))) {
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

        foreach ($this->sections as $name => $label) {
            $out .= sprintf("\n### %s changes (%s)\n\n", $label, $version);
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
            $id = Hash::get($item, 'number');
            $url = Hash::get($item, 'html_url');
            $title = Hash::get($item, 'title');
            $res .= sprintf("* [#%s](%s) %s\n", $id, $url, $title);
        }

        return $res;
    }
}
