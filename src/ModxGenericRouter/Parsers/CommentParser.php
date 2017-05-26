<?php

namespace ModxGenericRouter\Parsers;

use ModxGenericRouter\Tokens\DashToken;
use ModxGenericRouter\Tokens\Modx\Comment;
use ModxGenericRouter\Tree\Node;

class CommentParser extends BaseParser
{
    public static function handle(Node $node)
    {
        // Clean up the prefix here
        self::cleanupPrefix($node, DashToken::class);

        $comment = new Comment();
        $comment->setChildren($node->getChildren());
        return $comment;
    }
}
