<?php

namespace ModxGenericRouter\Tokens;

class NewlineToken extends BaseToken
{
    public static $TOKEN = ["\n", "\r\n", PHP_EOL];

    public function __toString()
    {
        if ($this->length !== null and $this->length > 1) {
            return '<newline*' . $this->length . '>';
        }

        return '<newline>';
    }

    public function toArray()
    {
        return PHP_EOL;
    }
}
