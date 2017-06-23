<?php

namespace ModxGenericRouter\Tokens;

abstract class BaseToken
{
    protected $children;
    protected $value;

    public static $TOKEN = [''];

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function toArray()
    {
        return self::$TOKEN[0];
    }

    public function __toString()
    {
        return '<' . $this->value . '>';
    }
}
