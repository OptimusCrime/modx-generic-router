<?php

include 'vendor/autoload.php';

use ModxGenericRouter\Expression;

echo '[[~16]]/{page:\d}/[[16=alias|uri]]' . PHP_EOL . PHP_EOL;

$expression = new Expression('[[~16]]/{page:\d}/[[16=alias|uri]]');
$expression->parse();

echo (string) $expression;

//var_dump($expression);
