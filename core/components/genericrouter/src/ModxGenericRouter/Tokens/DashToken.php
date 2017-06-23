<?php

namespace ModxGenericRouter\Tokens;

class DashToken extends BaseToken
{
    public static $TOKEN = ['-'];

    public function __construct()
    {
        $this->value = '-';
    }
}
