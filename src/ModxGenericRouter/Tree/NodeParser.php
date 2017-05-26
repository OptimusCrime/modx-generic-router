<?php

namespace ModxGenericRouter\Tree;

use ModxGenericRouter\Parsers\ChunkParser;
use ModxGenericRouter\Parsers\CommentParser;
use ModxGenericRouter\Parsers\LinkParser;
use ModxGenericRouter\Parsers\SnippetParser;
use ModxGenericRouter\Parsers\VariableParser;
use ModxGenericRouter\Tokens\AsteriskToken;
use ModxGenericRouter\Tokens\DashToken;
use ModxGenericRouter\Tokens\DollarSignToken;
use ModxGenericRouter\Tokens\ExclamationPointToken;
use ModxGenericRouter\Tokens\PlusSignToken;
use ModxGenericRouter\Tokens\Groups\TextToken;
use ModxGenericRouter\Tokens\TildeToken;

class NodeParser
{
    public static function parse(Node $node)
    {
        $newChildren = [];
        foreach ($node->getChildren() as $child) {
            if ($child instanceof Node) {
                $newChild = self::handleNode($child);
                if ($newChild === null) {
                    continue;
                }

                $newChildren[] = $newChild;

                continue;
            }

            $newChildren[] = $child;
        }

        $node->setChildren($newChildren);

        return $node;
    }

    public static function handleNode(Node $node, $child = null)
    {
        if (count($node->getChildren()) === 0) {
            return null;
        }

        if ($child === null) {
            $child = $node->getChild(0);
        }

        switch (true) {
            case $child instanceof TextToken:
                // This node is a snippet
                return SnippetParser::handle($node);
            case $child instanceof ExclamationPointToken:
                // Handling for Nodes which first child is an exclamation point. Call this method again and evaluate
                // on the second child instead of the first
                if (!$node->hasChild(1)) {
                    return null;
                }

                return self::handleNode($node, $node->getChild(1));
            case $child instanceof PlusSignToken:
            case $child instanceof AsteriskToken:
                // This node is a placeholder, system setting or (template) variable
                return VariableParser::handle($node);
            case $child instanceof TildeToken:
                // This node is a link
                return LinkParser::handle($node);
            case $child instanceof DollarSignToken:
                // This node is a chunk
                return ChunkParser::handle($node);
            case $child instanceof DashToken:
                // This node is a chunk
                return CommentParser::handle($node);
        }

        return null;
    }
}
