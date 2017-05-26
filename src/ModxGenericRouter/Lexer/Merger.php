<?php

namespace ModxGenericRouter\Lexer;

use ModxGenericRouter\Iterators\TokenIterator;
use ModxGenericRouter\Tokens\BaseToken;
use ModxGenericRouter\Tokens\BracketsToken;
use ModxGenericRouter\Tokens\NewlineToken;
use ModxGenericRouter\Tokens\RegularToken;
use ModxGenericRouter\Tokens\WhiteSpaceToken;
use ModxGenericRouter\Tokens\Groups\TagEndToken;
use ModxGenericRouter\Tokens\Groups\TagStartToken;
use ModxGenericRouter\Tokens\Groups\TextToken;

class Merger extends BaseLexer
{
    private $iterator;

    public function __construct()
    {
        parent::__construct();

        $this->iterator = new TokenIterator();
    }

    public function rebuild(array $tokens)
    {
        $this->iterator->setContent($tokens);

        while ($this->iterator->exists()) {
            $current = $this->iterator->getCurrent();

            switch (true) {
                case $current instanceof RegularToken:
                    $this->handleRegularToken($current);
                    break;
                case $current instanceof BracketsToken:
                    $this->handleBracketToken($current);
                    break;
                case $current instanceof WhiteSpaceToken;
                case $current instanceof NewlineToken;
                    $this->handleLineTokens($current);
                    break;
                default:
                    $this->tokens[] = $current;
                    $this->iterator->goForward();
            }
        }
    }

    private function handleRegularToken(BaseToken $current)
    {
        $stringArr = [$current];
        $offset = 1;
        while (true) {
            $ahead = $this->iterator->get($offset);

            if ($ahead instanceof RegularToken) {
                $stringArr[] = $ahead;

                $offset++;

                continue;
            }

            break;
        }

        $stringToken = new TextToken();
        $stringToken->setExpression($stringArr);

        $this->tokens[] = $stringToken;

        $this->iterator->goForward($offset);
    }

    private function handleBracketToken(BaseToken $current)
    {
        $next = $this->iterator->getNext();

        if ($current instanceof BracketsToken and $next instanceof BracketsToken and
            $current->getValue() == $next->getValue()) {
            if ($current->getValue() == '[') {
                $this->tokens[] = new TagStartToken();
            }
            else {
                $this->tokens[] = new TagEndToken();
            }

            $this->iterator->goForward(2);

            return;
        }

        $this->tokens[] = $current;

        $this->iterator->goForward();
    }

    private function handleLineTokens(BaseToken $current)
    {
        $offset = 1;
        while (true) {
            $ahead = $this->iterator->get($offset);

            if ($ahead instanceof $current) {
                $offset++;
                continue;
            }

            break;
        }

        $current->setLength($offset);
        $this->tokens[] = $current;

        $this->iterator->goForward($offset);
    }
}
