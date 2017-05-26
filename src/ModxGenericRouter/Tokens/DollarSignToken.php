<?php

namespace ModxGenericRouter\Tokens;

class DollarSignToken extends BaseToken
{
    public static $TOKEN = ['$'];

    public function __toString()
    {
        return '<$>';
    }
}
