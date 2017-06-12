<?php

$paths = [
    dirname(dirname(dirname(dirname(__FILE__)))),
    dirname(dirname(dirname(dirname(dirname(__FILE__)))))
];

foreach ($paths as $path) {
    $fullPath = $path . '/config.core.php';
    if (file_exists($fullPath)) {
        require_once $fullPath;
        break;
    }
}

require_once MODX_CORE_PATH . 'config/' . MODX_CONFIG_KEY . '.inc.php';
require_once MODX_CONNECTORS_PATH . 'index.php';

$corePath = $modx->getOption('genericrouter.core_path', null, $modx->getOption('core_path') . 'components/genericrouter/');
require_once $corePath . 'model/genericrouter/genericrouter.class.php';

$modx->genericRouter = new GenericRouter($modx);

$modx->lexicon->load('genericrouter:default');

$path = $modx->getOption('processorsPath', $modx->genericRouter->getConfig(), $corePath . 'processors/');
$modx->request->handleRequest(array(
    'processors_path' => $path,
    'location' => '',
));
