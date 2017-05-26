<?php

namespace ModxGenericRouter\Tokens;

abstract class BaseToken
{
    protected $children;
    protected $value;
    protected $length;

    public static $TOKEN = [''];

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setLength($length)
    {
        $this->length = $length;
    }

    public function toArray()
    {
        return self::$TOKEN[0];
    }
}
