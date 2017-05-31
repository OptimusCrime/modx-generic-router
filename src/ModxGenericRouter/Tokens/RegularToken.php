<?php

namespace ModxGenericRouter\Tokens;

class RegularToken extends BaseToken
{
    public static $TOKEN = [null];

    public function __toString()
    {
        return '<' . $this->value . '>';
    }

    public function getPretty()
    {
        return $this->value;
    }

    public function isInteger()
    {
        return is_int($this->value);
    }
}
