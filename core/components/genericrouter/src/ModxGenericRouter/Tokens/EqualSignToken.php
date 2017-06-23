<?php

namespace ModxGenericRouter\Tokens;

class EqualSignToken extends BaseToken
{
    public static $TOKEN = ['='];

    public function __construct()
    {
        $this->value = '=';
    }
}
