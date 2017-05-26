<?php

namespace ModxGenericRouter\Parser;

use ModxGenericRouter\Iterators\TokenIterator;
use ModxGenericRouter\Tokens\BracketsToken;
use ModxGenericRouter\Tokens\Groups\TagEndToken;
use ModxGenericRouter\Tokens\Groups\TagStartToken;


class Concatenate
{
    public static function run($tokens)
    {
        $newTokens = [];
        $iterator = new TokenIterator($tokens);
        while ($iterator->hasNext()) {
            $current = $iterator->getNext();

            // The current token is not a bracket, or the current token is the last
            if (!($current instanceof BracketsToken) or !$iterator->hasNext(2)) {
                $newTokens[] = $current;
                $iterator->goForward();
                continue;
            }

            // We have a next token
            $next = $iterator->getNext(2);

            // The next token is not a bracket, or the brackets are not the same way
            $similarBrackets = $current->getValue() == $next->getValue();
            if (!($next instanceof BracketsToken) or ($next instanceof BracketsToken and !$similarBrackets)) {
                $newTokens[] = $current;
                $iterator->goForward();
                continue;
            }

            // If we get here we know for a fact that the two brackets are the same
            $newTokens[] = self::newTagToken($current);
            $iterator->goForward(2);
        }

        return $newTokens;
    }

    private static function newTagToken($token)
    {
        if ($token->getValue() == '[') {
            return new TagStartToken();
        }

        return  new TagEndToken();
    }
}
