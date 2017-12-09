<?php

require_once 'TestPluginApp/config/bootstrap.php';

\BEdita\Core\Plugin::load(
    'BEdita/DevTools',
    ['bootstrap' => true, 'routes' => true, 'path' => ROOT]
);
