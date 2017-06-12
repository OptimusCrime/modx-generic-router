<?php

namespace ModxGenericRouter\Utilities;

class Formats
{
    public static function isInteger($value)
    {
        return is_int($value);
    }

    public static function isAlpha($value)
    {
        // Using this method...
        return ctype_alpha($value);
    }
}
