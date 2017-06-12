<?php

$settings = array();

$settings['modxgenericrouter.lazy'] = $modx->newObject('modSystemSetting');
$settings['modxgenericrouter.lazy']->fromArray(array(
    'key' => 'modxgenericrouter.lazy',
    'value' => true,
    'xtype' => 'boolean',
    'namespace' => 'modxgenericrouter',
    'area' => '',
), '', true, true);

return $settings;
