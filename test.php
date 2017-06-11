<?php

include 'vendor/autoload.php';

use ModxGenericRouter\Expression;

echo '[[~16]]/{page:\d}/[[16=alias|uri]]' . PHP_EOL . PHP_EOL;

$expression = new Expression('[[~16]]/{page:\d}/[[16=alias|uri]]');
$expression->parse();

echo (string) $expression . PHP_EOL . PHP_EOL;

$expression2 = new Expression('');
$expression2->load('{"version":"0.0.1","tree":[{"raw":false,"url":true,"id":false,"systemSetting":false,"depth":0,"fields":null,"content":"16"},{"raw":true,"url":false,"id":false,"systemSetting":false,"depth":0,"fields":null,"content":"\/{page:\\\d}\/"},{"raw":false,"url":false,"id":false,"systemSetting":false,"depth":0,"fields":[{"templateVariable":false,"value":"alias"},{"templateVariable":false,"value":"uri"}],"content":"16"}]}');

echo (string) $expression2 . PHP_EOL . PHP_EOL;

$expression3 = new Expression('[[~1]]/{page:\d}/[[16=alias|uri]]');
$expression3->parse();
var_dump($expression3->isDynamic());
var_dump($expression3->isNested());
var_dump($expression3->isDeterministic());

//var_dump($expression);
