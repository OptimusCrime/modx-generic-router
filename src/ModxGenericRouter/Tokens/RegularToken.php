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
}
