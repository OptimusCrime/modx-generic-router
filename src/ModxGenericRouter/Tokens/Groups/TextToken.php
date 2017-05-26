<?php

namespace ModxGenericRouter\Tokens\Groups;

class TextToken
{
    private $expression;

    public function setExpression(array $expression)
    {
        $this->expression = $expression;
    }

    public function __toString()
    {
        $output = '<';
        foreach ($this->expression as $expression) {
            $output .= (string) $expression;
        }
        $output .= '>';

        return $output;
    }

    public function getPretty()
    {
        $output = '';
        foreach ($this->expression as $expression) {
            $output .= $expression->getValue();
        }

        return $output;
    }

    public function toArray()
    {
        return $this->getPretty();
    }
}
