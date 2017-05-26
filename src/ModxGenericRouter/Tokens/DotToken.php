<?php

namespace ModxGenericRouter\Tokens;

class DotToken extends BaseToken
{
    public static $TOKEN = ['.'];

    public function __toString()
    {
        return '<.>';
    }
}
