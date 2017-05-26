<?php

namespace ModxGenericRouter\Parser;

use ModxGenericRouter\Parser\Tree\Node;
use ModxGenericRouter\Tokens\Groups\TextToken;

class Relax
{
    public static function run(Node $rootNode)
    {
        $newChildren = [];
        $collection = null;
        foreach ($rootNode->getChildren() as $child) {
            if ($child instanceof Node) {
                if ($collection !== null) {
                    $newChildren[] = $collection;
                    $collection = null;
                }

                $newChildren[] = $child;
                continue;
            }

            if ($collection === null) {
                $collection = new TextToken();
            }

            $collection->addText($child->getValue());
        }

        $rootNode->setChildren($newChildren);
    }
}
