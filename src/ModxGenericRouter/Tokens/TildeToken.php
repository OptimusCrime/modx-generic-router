<?php

namespace ModxGenericRouter\Tokens;

class TildeToken extends BaseToken
{
    public static $TOKEN = ['~'];

    public function __toString()
    {
        return '<~>';
    }
}
