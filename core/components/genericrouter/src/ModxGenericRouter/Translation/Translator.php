<?php
namespace OptimusCrime\ModxGenericRouter\Translation;

use OptimusCrime\ModxGenericRouter\ModxGenericRouter;

class Translator
{
    public static function encode(array $data)
    {
        return json_encode($data);
    }

    public static function decode($data)
    {
        $data = json_decode($data, true);
        if ($data['version'] === ModxGenericRouter::VERSION) {
            return $data['tree'];
        }

        return self::convert($data['tree'], $data['version'], ModxGenericRouter::VERSION);
    }

    private static function convert(array $data, $from, $to)
    {
        // TODO: For later, implement conversion system here that fetches a graph over the to and from conversion(s)
        // needed to convert the encoded data into the current version.
        return $data;
    }
}
