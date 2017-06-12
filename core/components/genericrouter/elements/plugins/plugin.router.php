<?php
if ($modx->event->name === 'OnPageNotFound') {
    $path = $modx->getOption('genericrouter.core_path', null, $modx->getOption('core_path') . 'components/genericrouter/');
    $genericRouter = $modx->getService('genericrouter', 'GenericRouter', $path . 'model/genericrouter/');

    if (!($genericRouter instanceof GenericRouter)) {
        $modx->log(modX::LOG_LEVEL_ERROR, 'Could not load GenericRouter class');
        return;
    }

    $genericRouter->handleRequest();
}
