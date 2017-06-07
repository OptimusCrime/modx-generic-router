<?php

namespace ModxGenericRouter;

use ModxGenericRouter\Lexer\Lexer;
use ModxGenericRouter\Parser\Parser;


class Expression
{
    private $expression;
    private $valid;
    private $errors;
    private $warnings;
    private $tree;

    public function __construct($expression)
    {
        $this->expression = $expression;

        $this->valid = false;
        $this->errors = [];
        $this->warnings = [];

        $this->tree = null;
    }

    public function isValid()
    {
        return $this->valid;
    }

    public function parse()
    {
        $tokens = Lexer::tokenize($this->expression);
        $this->tree = Parser::parse($tokens);
        $this->validate();
    }

    private function validate()
    {
        // $validator = new Validator($this->tree);
        // $this->tree = $validator->isValid();
        // ...
    }

    public function __toString()
    {
        return (string) $this->tree;
    }

    public function dump()
    {
        // TODO
    }

    public function load()
    {
        // TODO
    }
}
