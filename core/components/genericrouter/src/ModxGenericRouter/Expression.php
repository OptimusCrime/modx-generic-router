<?php
namespace OptimusCrime\ModxGenericRouter;

use Exception;
use OptimusCrime\ModxGenericRouter\DSN\Fragment;
use OptimusCrime\ModxGenericRouter\Lexer\Lexer;
use OptimusCrime\ModxGenericRouter\Parser\Parser;

class Expression
{
    private Parser $parser;

    private string $expression;
    private bool $valid;
    private array $errors;
    private array $warnings;
    private ?array $tree;

    public function __construct($expression)
    {
        $this->parser = new Parser();

        $this->expression = $expression;

        $this->valid = true;

        $this->valid = false;
        $this->errors = [];
        $this->warnings = [];

        $this->tree = null;
    }

    public function isValid(): bool
    {
        return $this->valid;
    }

    /**
     * @throws Exception
     */
    public function parse(): void
    {
        var_dump($this->expression);
        $lexer = new Lexer($this->expression);
        $lexer->tokenize();
        $tokens = $lexer->getTokens();
        var_dump($this->expression);
        var_dump($tokens);
        die();
        //$this->parser->run($tokens);
        //$this->tree = $this->parser->run($tokens);

        //$this->validate();
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

    public function getTree()
    {
        return $this->tree;
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
