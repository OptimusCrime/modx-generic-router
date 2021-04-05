<?php
namespace OptimusCrime\ModxGenericRouter\Utilities;

class Formats
{
    public static function isInteger($value): bool
    {
        return is_int($value);
    }

    public static function isAlpha($value): bool
    {
        // Using this method...
        return ctype_alpha($value);
    }
}
