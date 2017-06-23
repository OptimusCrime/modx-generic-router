<?php

namespace ModxGenericRouter\Parser;

use ModxGenericRouter\Parser\Tree\Node;
use ModxGenericRouter\DSN\Fragment;

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
                $collection = new Fragment();
                $collection->setRaw(true);
            }

            $collection->addContent($child->getValue());
        }

        if ($collection !== null) {
            $newChildren[] = $collection;
        }

        $rootNode->setChildren($newChildren);
    }
}
