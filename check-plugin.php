<?php

require_once __DIR__ . '/vendor/autoload.php';

use WP_Since\Runner\PluginCheckCommand;

$pluginPath = $argv[1] ?? getcwd();
$sinceMapPath = __DIR__ . '/wp-since.json';

exit(PluginCheckCommand::run($pluginPath, $sinceMapPath));
