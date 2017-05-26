<?php

namespace ModxGenericRouter\Tokens;

class ExclamationPointToken extends BaseToken
{
    public static $TOKEN = ['!'];

    public function __toString()
    {
        return '<!>';
    }
}
