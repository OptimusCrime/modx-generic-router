<?php

namespace ModxGenericRouter\Parser;

use ModxGenericRouter\Parser\Tree\Node;


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
        foreach ($node->getChildren() as $child) {
            echo (string) $child . PHP_EOL;
        }

        echo '------' . PHP_EOL . PHP_EOL;
    }
}
