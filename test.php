<?php

include 'vendor/autoload.php';

use ModxGenericRouter\ModxGenericRouter;

$expression = ModxGenericRouter::parse('[[~16]]/{page:\d}/[[16=alias|uri]]');

echo (string) $expression;

//var_dump($expression);
