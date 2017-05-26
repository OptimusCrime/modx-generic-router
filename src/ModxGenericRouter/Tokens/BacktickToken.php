<?php

namespace ModxGenericRouter\Tokens;

class BacktickToken extends BaseToken
{
    public static $TOKEN = ['`'];

    public function __toString()
    {
        return '<`>';
    }
}
