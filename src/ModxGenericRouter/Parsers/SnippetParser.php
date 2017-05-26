<?php

namespace ModxGenericRouter\Parsers;

use ModxGenericRouter\Log\Logger;
use ModxGenericRouter\Tokens\Modx\Snippet;
use ModxGenericRouter\Tree\Node;

class SnippetParser extends BaseParser
{
    public static function handle(Node $node)
    {
        $isCached = self::checkIfCached($node);

        // Make sure we have a name
        $name = self::getName($node);
        if ($name === null) {
            Logger::getInstance()->addLine('Encountered snippet with invalid name.');

            return null;
        }

        // Remove all whitespace and newlines from the children here
        self::cleanContext($node);

        // Check if we have any properties to parse
        $properties = [];
        if (self::hasProperties($node)) {
            $properties = self::parseProperties($node);

            if (count($properties) === 0) {
                Logger::getInstance()->addLine('Found ? in snippet call, indicating properties. Found no properties.');
            }
        }

        $snippet = new Snippet();
        $snippet->setName($name);
        $snippet->setCached($isCached);
        $snippet->setProperties($properties);
        $snippet->setChildren($node->getChildren());

        return $snippet;
    }
}
