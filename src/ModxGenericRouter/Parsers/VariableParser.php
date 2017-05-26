<?php

namespace ModxGenericRouter\Parsers;

use ModxGenericRouter\Tokens\Modx\Variable;
use ModxGenericRouter\Tree\Node;

class VariableParser extends BaseParser
{
    public static function handle(Node $node)
    {
        $isCached = self::checkIfCached($node);

        // Get the type of variable (placeholder, system setting or variable / template variable)
        $type = self::getVariableType($node);
        if ($type === null) {
            return null;
        }

        // Make sure we have a name
        $name = self::getName($node);
        if ($name === null) {
            return null;
        }

        // Check if we have any output filters
        $hasOutputFilters = self::hasOutputFilters($node);
        $outputFilters = [];
        if ($hasOutputFilters) {
            $outputFilters = self::parseOutputFilters($node);
        }

        $variable = new Variable();
        $variable->setName($name);
        $variable->setCached($isCached);
        $variable->setChildren($node->getChildren());

        $variable->setData('type', $type);
        $variable->setData('output_filters', $outputFilters);

        return $variable;
    }
}
