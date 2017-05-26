<?php

namespace ModxGenericRouter;

use ModxGenericRouter\Lexer\Lexer;
use ModxGenericRouter\Parser\Parser;


class ModxGenericRouter
{
    public function __construct()
    {
        //
    }

    public static function parse($expression)
    {
        $tokens = Lexer::tokenize($expression);
        $ast = Parser::parse($tokens);
        return $ast;
    }
}
