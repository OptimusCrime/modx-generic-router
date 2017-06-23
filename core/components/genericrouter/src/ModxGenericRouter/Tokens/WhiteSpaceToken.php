<?php

namespace ModxGenericRouter\Tokens;

class WhiteSpaceToken extends BaseToken
{
    public static $TOKEN = [' '];

    public function __construct()
    {
        $this->value = ' ';
    }
}
