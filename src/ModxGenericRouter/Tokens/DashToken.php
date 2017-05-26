<?php

namespace ModxGenericRouter\Tokens;

class DashToken extends BaseToken
{
    public static $TOKEN = ['-'];

    public function __toString()
    {
        return '<->';
    }
}
