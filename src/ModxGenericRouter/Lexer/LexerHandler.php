<?php

namespace ModxGenericRouter\Lexer;

class LexerHandler
{
    private $tokens;

    public function __construct()
    {
        $this->tokens = [];
    }

    public function run($content)
    {
        $lexer = new Lexer();
        $lexer->tokenize($content);

        $merger = new Merger();
        $merger->rebuild($lexer->getTokens());

        $this->tokens = $merger->getTokens();
    }

    public function getTokens()
    {
        return $this->tokens;
    }
}
