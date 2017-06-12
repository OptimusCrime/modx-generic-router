<?php

namespace ModxGenericRouter\Tokens;

class PlusSignToken extends BaseToken
{
    public static $TOKEN = ['+'];

    public function __toString()
    {
        return '<+>';
    }
}
