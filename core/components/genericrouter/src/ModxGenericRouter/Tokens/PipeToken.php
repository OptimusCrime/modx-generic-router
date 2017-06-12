<?php

namespace ModxGenericRouter\Tokens;

class PipeToken extends BaseToken
{
    public static $TOKEN = ['|'];

    public function __toString()
    {
        return '<|>';
    }
}
