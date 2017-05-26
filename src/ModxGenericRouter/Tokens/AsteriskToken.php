<?php

namespace ModxGenericRouter\Tokens;

class AsteriskToken extends BaseToken
{
    public static $TOKEN = ['*'];

    public function __toString()
    {
        return '<*>';
    }
}
