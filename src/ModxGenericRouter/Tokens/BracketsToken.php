<?php

namespace ModxGenericRouter\Tokens;

class BracketsToken extends BaseToken
{
    public static $TOKEN = ['[', ']'];

    public function __toString()
    {
        return '<' . $this->value . '>';
    }
}
