<?php

namespace ModxGenericRouter\Tokens;

class ColonToken extends BaseToken
{
    public static $TOKEN = ['*'];

    public function __toString()
    {
        return '<:>';
    }
}

