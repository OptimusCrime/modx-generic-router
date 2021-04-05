<?php
namespace OptimusCrime\ModxGenericRouter;

use OptimusCrime\ModxGenericRouter\Translation\Translator;


class ModxGenericRouter
{
    const VERSION = '0.0.1';

    const TRANSLATOR = Translator::class;

    public static function getConversion()
    {
        return [];
    }
}
