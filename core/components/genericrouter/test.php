<?php
require __DIR__ . '/vendor/autoload.php';

use OptimusCrime\ModxGenericRouter\Expression;

// ^(?P<url>~)?(?P<id>\d+|(?:(?:\+\+)(?P<ss>[a-zA-Z_]+)))(?:=(?P<attr>[a-zA-Z._|]+))?$
$expressions = [
    "[[~16]]/foo/bar/[[16=id]]",
    "[[~16=id]]/foo/bar",
    "[[~16=id]]/foo/bar",
    "[[~16=id|tv.foobar]]/foo/bar",
    "[[~16]]/{page:\d}",
    "[[16=tv.my_tv_here]]",
    "[[~++start_page]]",
    "[[~16=tv.my_tv_here]]",
    "[[16=alias|uri]]"
    //"~a~a~a~",
    //"a~a",
];


foreach ($expressions as $expression) {
    $testExpression = new Expression($expression);
    $testExpression->parse();
    die();
}

//var_dump($testExpression->dump());
