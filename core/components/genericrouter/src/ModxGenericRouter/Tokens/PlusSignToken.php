<?php

namespace ModxGenericRouter\Tokens;

class PlusSignToken extends BaseToken
{
    public static $TOKEN = ['+'];

    public function __construct()
    {
        $this->value = '+';
    }
}
