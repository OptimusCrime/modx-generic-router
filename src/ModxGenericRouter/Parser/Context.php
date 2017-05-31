<?php

namespace ModxGenericRouter\Parser;

use ModxGenericRouter\Parser\Tree\Node;
use ModxGenericRouter\Tokens\RegularToken;
use ModxGenericRouter\Tokens\TildeToken;


class Context
{
    public static function parse(Node $rootNode)
    {
        foreach ($rootNode->getChildren() as $child) {
            if ($child instanceof Node) {
                self::parseNode($child);
            }
        }
    }

    private static function parseNode(Node $node)
    {
        if (count($node->getChildren()) === 0) {
            // Set error
        }

        $firstChild = $node->getChild(0);

        // First child has to be one of
        switch(true) {
            case $firstChild instanceof TildeToken:
                //
                break;
            case $firstChild instanceof RegularToken and $firstChild->isInteger():
                //
                break;
            default:
                // Error
                break;
        }


        foreach ($node->getChildren() as $child) {
            echo (string) $child . PHP_EOL;
        }

        echo '------' . PHP_EOL . PHP_EOL;
    }
}
