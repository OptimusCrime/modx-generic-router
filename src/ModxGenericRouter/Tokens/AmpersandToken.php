<?php

namespace ModxGenericRouter\Tokens;

class AmpersandToken extends BaseToken
{
    public static $TOKEN = ['&'];

    public function __toString()
    {
        return '<&>';
    }
}
