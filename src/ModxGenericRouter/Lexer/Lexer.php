<?php

namespace ModxGenericRouter\Lexer;

use ModxGenericRouter\Iterators\ContentIterator;
use ModxGenericRouter\Tokens\BracketsToken;
use ModxGenericRouter\Tokens\DashToken;
use ModxGenericRouter\Tokens\DotToken;
use ModxGenericRouter\Tokens\EqualSignToken;
use ModxGenericRouter\Tokens\NewlineToken;
use ModxGenericRouter\Tokens\PipeToken;
use ModxGenericRouter\Tokens\PlusSignToken;
use ModxGenericRouter\Tokens\RegularToken;
use ModxGenericRouter\Tokens\TildeToken;
use ModxGenericRouter\Tokens\WhiteSpaceToken;

class Lexer
{

    public static function tokenize($expression)
    {
        $tokens = [];
        $iterator = new ContentIterator($expression);
        while ($iterator->hasNext()) {
            $current = $iterator->getNext();

            if ($current === null or $current === '') {
                continue;
            }

            $tokens[] = Lexer::identifyToken($current);
        }

        return $tokens;
    }

    private static function identifyToken($value)
    {
        switch ($value) {
            case '[':
            case ']':
                $token = new BracketsToken();
                $token->setValue($value);
                return $token;
            case '=':
                return new EqualSignToken();
            case '+':
                return new PlusSignToken();
            case '|':
                return new PipeToken();
            case '.':
                return new DotToken();
            case '~':
                return new TildeToken();
            case '-':
                return new DashToken();
            case "\n":
            case "\r\n":
            case PHP_EOL:
                return new NewlineToken();
            case ' ':
                return new WhiteSpaceToken();
            default:
                $token = new RegularToken();
                $token->setValue($value);
                return $token;
        }
    }
}
