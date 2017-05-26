<?php

namespace ModxGenericRouter\Lexer;

use ModxGenericRouter\Iterators\ContentIterator;
use ModxGenericRouter\Tokens\AmpersandToken;
use ModxGenericRouter\Tokens\AsteriskToken;
use ModxGenericRouter\Tokens\BacktickToken;
use ModxGenericRouter\Tokens\BracketsToken;
use ModxGenericRouter\Tokens\ColonToken;
use ModxGenericRouter\Tokens\DashToken;
use ModxGenericRouter\Tokens\DollarSignToken;
use ModxGenericRouter\Tokens\EqualSignToken;
use ModxGenericRouter\Tokens\ExclamationPointToken;
use ModxGenericRouter\Tokens\NewlineToken;
use ModxGenericRouter\Tokens\PlusSignToken;
use ModxGenericRouter\Tokens\RegularToken;
use ModxGenericRouter\Tokens\QuestionMarkToken;
use ModxGenericRouter\Tokens\TildeToken;
use ModxGenericRouter\Tokens\WhiteSpaceToken;

class Lexer extends BaseLexer
{
    private $iterator;

    public function __construct()
    {
        parent::__construct();

        $this->iterator = new ContentIterator();
    }

    public function tokenize($content)
    {
        $this->iterator->setContent($content);
        while ($this->iterator->hasNext()) {
            $current = $this->iterator->getNext();

            if ($current === null or $current === '') {
                continue;
            }

            $this->tokens[] = $this->identifyToken($current);
        }
    }

    private function identifyToken($value)
    {
        switch ($value) {
            case '[':
            case ']':
                $token = new BracketsToken();
                $token->setValue($value);
                return $token;
            case '!':
                return new ExclamationPointToken();
            case '?':
                return new QuestionMarkToken();
            case '&':
                return new AmpersandToken();
            case '=':
                return new EqualSignToken();
            case '`':
                return new BacktickToken();
            case '+':
                return new PlusSignToken();
            case '*':
                return new AsteriskToken();
            case ':':
                return new ColonToken();
            case '~':
                return new TildeToken();
            case '$':
                return new DollarSignToken();
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

    public function merge()
    {
        $merger = new Merger();
        $merger->rebuild($this->tokens);

        $this->tokens = $merger->getTokens();
    }
}
