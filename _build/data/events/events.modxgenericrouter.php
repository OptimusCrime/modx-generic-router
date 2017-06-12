<?php

$events = array();

$events['OnWebPageInit'] = $modx->newObject('modPluginEvent');
$events['OnWebPageInit']->fromArray(array(
    'event' => 'OnPageNotFound',
    'priority' => 0,
    'propertyset' => 0,
), '', true, true);

return $events;
