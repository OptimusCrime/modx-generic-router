<?php

namespace ModxGenericRouter\Tokens;

class NewlineToken extends BaseToken
{
    public static $TOKEN = ["\n", "\r\n", PHP_EOL];

    public function __construct()
    {
        $this->value = PHP_EOL;
    }

    public function __toString()
    {
        return '<newline>';
    }

    public function toArray()
    {
        return PHP_EOL;
    }
}
