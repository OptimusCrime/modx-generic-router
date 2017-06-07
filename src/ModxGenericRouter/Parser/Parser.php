<?php

namespace ModxGenericRouter\Parser;


class Parser
{

    public static function parse(array $tokens)
    {
        // First we make sure to concatenate every [[ and ]] into their own group tokens
        $tokens = Concatenate::run($tokens);

        // Structure the tokens into a tree
        $tree = Structure::run($tokens);

        // Relax the tree, e.i. turn everything "back" to normal string that is not inside a tag
        Relax::run($tree);

        // Parse based on context
        Context::parse($tree);

        // Return the final tree
        return $tree;
    }
}
