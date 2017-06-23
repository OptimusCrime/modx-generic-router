<?php

namespace ModxGenericRouter\Tokens;

use ModxGenericRouter\Utilities\Formats;

class RegularToken extends BaseToken
{
    const INTEGER = 0;
    const ALPHA = 1;

    public static $TOKEN = [null];

    public function __construct()
    {
        $this->value = '';
    }

    public function getPretty()
    {
        return $this->value;
    }

    public function isInteger()
    {
        return Formats::isInteger($this->value);
    }

    public function isAlpha()
    {
        return Formats::isAlpha($this->value);
    }
}
