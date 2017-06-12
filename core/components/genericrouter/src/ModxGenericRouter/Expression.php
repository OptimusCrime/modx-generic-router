<?php

namespace ModxGenericRouter;

use ModxGenericRouter\DSN\Fragment;
use ModxGenericRouter\Lexer\Lexer;
use ModxGenericRouter\Parser\Parser;
use ModxGenericRouter\Parser\Tree\Node;
use ModxGenericRouter\Translation\Translator;


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
        return implode('', $this->tree);
    }

    public function dump()
    {
        $output = [];
        foreach ($this->tree as $fragment) {
            $output[] = $fragment->toArray();
        }

        $data = [
            'version' => ModxGenericRouter::VERSION,
            'tree' => $output
        ];

        return (ModxGenericRouter::TRANSLATOR)::encode($data);
    }

    public function load($string)
    {
        $data = (ModxGenericRouter::TRANSLATOR)::decode($string);

        $this->tree = [];
        foreach ($data as $item) {
            $this->tree[] = Fragment::fromArray($item);
        }
    }

    public function isDynamic()
    {
        foreach ($this->tree as $fragment) {
            if (!$fragment->isRaw()) {
                return True;
            }
        }

        return false;
    }

    public function isNested()
    {
        foreach ($this->tree as $fragment) {
            if ($fragment->getDepth() !== 0) {
                return true;
            }
        }

        return false;
    }

    public function isDeterministic()
    {
        // The Expression is deterministic if we have no system settings
        foreach ($this->tree as $fragment) {
            if ($fragment->isSystemSetting()) {
                return false;
            }
        }

        return true;
    }
}
