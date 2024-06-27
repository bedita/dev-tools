<?php
declare(strict_types=1);

/**
 * BEdita, API-first content management framework
 * Copyright 2020 ChannelWeb Srl, Chialab Srl
 *
 * This file is part of BEdita: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * See LICENSE.LGPL or <http://gnu.org/licenses/lgpl-3.0.html> for more details.
 */
namespace BEdita\DevTools\Command;

use AllowDynamicProperties;
use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Database\Connection;
use Cake\Datasource\ConnectionManager;
use Cake\Utility\Hash;

/**
 * Command to prepare `applications` and `users` to import a project in a new environment.
 * Since applications api keys and users passwords will generally differ this command will try to
 * sync those values, when possible, in order to enable migration.
 *
 * The new project database you want to import must be reachable via an `import` key Datasource configuration.
 * This new project will be modified this way:
 *  - new project `applications` must be present as name on the current project DB => current api keys are saved in the new imported project
 *  - users password hashes are changed (if set) in the new imported project using current users password hashes on records with the same `username`
 *
 * After this command is finished the new imported project can be used in the current environment and replace current project/database.
 *
 * @property \BEdita\Core\Model\Table\ApplicationsTable $Applications
 * @property \BEdita\Core\Model\Table\UsersTable $Users
 */
#[AllowDynamicProperties]
class ImportProjectCommand extends Command
{
    /**
     * {@inheritDoc}
     *
     * Main command execution:
     * - applications and users are loaded from current and imported project
     * - applications api keys and users passwords are updated to be used in current environment
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
        if (!in_array('import', ConnectionManager::configured())) {
            $io->error('Unable to connect to `import` datasource, please review "Datasource" configuration');
            $this->abort();
        }
        $importConnection = ConnectionManager::get('import');
        $defaultConnection = ConnectionManager::get('default');
        if (!$importConnection instanceof Connection || !$defaultConnection instanceof Connection) {
            $io->error('Wrong connection type, please review "Datasource" configuration');
            $this->abort();
        }

        // review `applications`
        $this->loadModel('Applications');
        $current = $this->loadApplications($defaultConnection);
        $import = $this->loadApplications($importConnection);
        $missing = array_diff(array_keys($import), array_keys($current));
        if (!empty($missing)) {
            $io->error(sprintf('Some applications are missing on current project: %s', implode(' ', $missing)));
            $this->abort();
        }
        $update = array_intersect_key($current, $import);
        $this->updateApplications($importConnection, $update);

        // review `users`
        $this->loadModel('Users');
        $current = $this->loadUsers($defaultConnection);
        $import = $this->loadUsers($importConnection);
        $missing = array_diff(array_keys($import), array_keys($current));
        if (!empty($missing)) {
            $io->warning(sprintf('Some users are missing in current project [%d]', count($missing)));

            if ($io->askChoice('Do you want to proceed?', ['y', 'n'], 'n') === 'n') {
                $io->error('Aborting.');
                $this->abort();
            }
        }
        $update = array_intersect_key($current, $import);
        $this->updateUsers($importConnection, $update);
        $io->success('Import project done.');
    }

    /**
     * Load applications on a given connection
     * Return an array having `name` as key,
     *
     * @param \Cake\Database\Connection $connection The Connection
     * @return array
     */
    protected function loadApplications(Connection $connection): array
    {
        $this->Applications->setConnection($connection);
        $apps = $this->Applications->find()->select(['name', 'api_key', 'client_secret'])->toArray();

        return Hash::combine($apps, '{n}.name', '{n}');
    }

    /**
     * Load users on a given connection
     * Return an array having `username` as key,
     *
     * @param \Cake\Database\Connection $connection The Connection
     * @return array
     */
    protected function loadUsers(Connection $connection): array
    {
        $this->Users->setConnection($connection);
        $users = $this->Users->find()->select(['username', 'password_hash'])->toArray();

        return Hash::combine($users, '{n}.username', '{n}');
    }

    /**
     * Update applications api keys using api keys provided in input array
     *
     * @param \Cake\Database\Connection $connection The connection
     * @param array $applications Application data
     * @return void
     */
    protected function updateApplications(Connection $connection, array $applications): void
    {
        $this->Applications->setConnection($connection);
        foreach ($applications as $name => $application) {
            /** @var \BEdita\Core\Model\Entity\Application $entity */
            $entity = $this->Applications->find()->where(['name' => $name])->firstOrFail();
            $entity->api_key = $application->api_key;
            $entity->client_secret = $application->client_secret;
            $this->Applications->saveOrFail($entity);
        }
    }

    /**
     * Update applications api keys using api keys provided in input array
     *
     * @param \Cake\Database\Connection $connection The connection
     * @param array $users Users data
     * @return void
     */
    protected function updateUsers(Connection $connection, array $users): void
    {
        foreach ($users as $username => $user) {
            if (!empty($user->password_hash)) {
                $query = sprintf(
                    "UPDATE users SET password_hash = '%s' WHERE username = '%s'",
                    $user->password_hash,
                    $username
                );
                $connection->execute($query);
            }
        }
    }
}
