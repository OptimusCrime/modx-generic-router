<?php

namespace ModxGenericRouter\Tokens\Groups;

class TextToken
{
    private $expression;

    public function setExpression()
    {
        $this->expression = '';
    }

    public function addText($text)
    {
        $this->expression .= $text;
    }

    public function __toString()
    {
        return $this->expression;
    }
}
