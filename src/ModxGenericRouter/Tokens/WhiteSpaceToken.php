<?php

namespace ModxGenericRouter\Tokens;

class WhiteSpaceToken extends BaseToken
{
    public static $TOKEN = [' '];

    public function __toString()
    {
        if ($this->length !== null and $this->length > 1) {
            return '<whitespace*' . $this->length . '>';
        }

        return '<whitespace>';
    }
}
