<?php

namespace ModxGenericRouter\Lexer;

abstract class BaseLexer
{
    protected $tokens;

    public function __construct()
    {
        $this->tokens = [];
    }

    public function getTokens()
    {
        return $this->tokens;
    }
}
