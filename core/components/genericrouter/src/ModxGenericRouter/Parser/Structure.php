<?php

namespace ModxGenericRouter\Parser;

use ModxGenericRouter\Iterators\TokenIterator;
use ModxGenericRouter\Parser\Tree\Node;
use ModxGenericRouter\Tokens\Groups\TagEndToken;
use ModxGenericRouter\Tokens\Groups\TagStartToken;


class Structure
{
    public static function run(array $tokens)
    {
        $rootNode = new Node();
        $rootNode->setRootNode(true);

        $iterator = new TokenIterator($tokens);
        self::build($rootNode, $iterator);

        return $rootNode;
    }

    private static function build(Node $node, TokenIterator $iterator)
    {
        while ($iterator->hasNext()) {
            $current = $iterator->getNext();

            // If the current token is not a tag, just add it as a child and continue
            if (!($current instanceof TagEndToken) and !($current instanceof TagStartToken)) {
                $node->addChild($current);
                $iterator->goForward();
                continue;
            }

            // We found a start tag. Create a new node, set relationships and recursion
            if ($current instanceof TagStartToken) {
                $newNode = new Node();
                $newNode->setParent($node);
                $node->addChild($newNode);

                $iterator->goForward();

                self::build($newNode, $iterator);
                continue;
            }

            // We found an end tag. Close the current node and jump out
            $node->setClosed(true);
            $iterator->goForward();
            return;
        }
    }
}
