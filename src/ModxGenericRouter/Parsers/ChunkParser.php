<?php

namespace ModxGenericRouter\Parsers;

use ModxGenericRouter\Log\Logger;
use ModxGenericRouter\Tokens\DollarSignToken;
use ModxGenericRouter\Tokens\Modx\Chunk;
use ModxGenericRouter\Tree\Node;

class ChunkParser extends BaseParser
{
    public static function handle(Node $node)
    {
        $isCached = self::checkIfCached($node);

        // Clean up the prefix here
        self::cleanupPrefix($node, DollarSignToken::class);

        // Make sure we have a name
        $name = self::getName($node);
        if ($name === null) {
            Logger::getInstance()->addLine('Encountered chunk with invalid name.');

            return null;
        }

        // Remove all whitespace and newlines from the children here
        self::cleanContext($node);

        // Check if we have any properties to parse
        $properties = [];
        if (self::hasProperties($node)) {
            $properties = self::parseProperties($node);

            if (count($properties) === 0) {
                Logger::getInstance()->addLine('Found ? in chunk call, indicating properties. Found no properties.');
            }
        }

        $chunk = new Chunk();
        $chunk->setName($name);
        $chunk->setCached($isCached);
        $chunk->setProperties($properties);
        $chunk->setChildren($node->getChildren());

        return $chunk;
    }
}
