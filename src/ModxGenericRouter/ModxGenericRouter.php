<?php

namespace ModxGenericRouter;


class ModxGenericRouter
{
    const VERSION = '0.0.1';

    const TRANSLATOR = \ModxGenericRouter\Translation\Translator::class;

    public static function getConversion()
    {
        return [];
    }
}
