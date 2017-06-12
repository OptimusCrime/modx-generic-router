<?php

$plugins = array();

/* create the plugin object */
$plugins[0] = $modx->newObject('modPlugin');
$plugins[0]->set('id', 1);
$plugins[0]->set('name', 'ModxGenericRouter');
$plugins[0]->set('description', '');
$plugins[0]->set('plugincode', getSnippetContent($sources['elements'] . 'plugins/plugin.router.php'));
$plugins[0]->set('category', 0);

$events = include $sources['events'] . 'events.modxgenericrouter.php';
if (is_array($events) && !empty($events)) {
    $plugins[0]->addMany($events);
    $modx->log(xPDO::LOG_LEVEL_INFO, 'Packaged in ' . count($events) . ' Plugin Events for Modx Generic Router.'); flush();
} else {
    $modx->log(xPDO::LOG_LEVEL_ERROR, 'Could not find plugin events for Modx Generic Router!');
}

unset($events);
return $plugins;
