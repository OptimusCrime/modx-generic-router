<?php

namespace ModxGenericRouter\Parsers;

use ModxGenericRouter\Tokens\Modx\Link;
use ModxGenericRouter\Tokens\TildeToken;
use ModxGenericRouter\Tree\Node;

class LinkParser extends BaseParser
{
    public static function handle(Node $node)
    {
        $isCached = self::checkIfCached($node);

        // Clean up the prefix here
        self::cleanupPrefix($node, TildeToken::class);

        $link = new Link();
        $link->setCached($isCached);
        return $link;
    }
}
