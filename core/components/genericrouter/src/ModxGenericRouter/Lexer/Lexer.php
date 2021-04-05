<?php
namespace OptimusCrime\ModxGenericRouter\Lexer;

use Exception;
use OptimusCrime\ModxGenericRouter\Iterators\ContentIterator;
use OptimusCrime\ModxGenericRouter\Tokens\Keywords;
use OptimusCrime\ModxGenericRouter\Tokens\Token;

class Lexer
{
    const MODE_STRING_LITERAL = 'string_literal';
    const MODE_PARSING_CONTENT = 'parsing_content';
    const MODE_END_OF_LINE = 'end_of_line';

    private ContentIterator $iterator;
    private array $tokens;
    private string $mode;

    public function __construct(string $expression)
    {
        $this->iterator = new ContentIterator($expression);
        $this->tokens = [];
        $this->mode = static::MODE_STRING_LITERAL;
    }

    public function getTokens(): array
    {
        return $this->tokens;
    }

    /**
     * @throws Exception
     */
    public function tokenize(): void
    {
        while ($this->iterator->peek() !== PHP_EOL) {
            // Check if we should switch back to string literal
            if ($this->checkEOLOrPreChangeParsing()) {
                continue;
            }

            switch ($this->mode) {
                case static::MODE_END_OF_LINE:
                    break;
                case static::MODE_STRING_LITERAL:
                    $this->parseStringLiteral();
                    break;
                case static::MODE_PARSING_CONTENT:
                    $this->parseContent();
                    break;
                default:
                    throw new Exception('Invalid lexer mode: ' . $this->mode);

            }
        }
    }

    /**
     * @throws Exception
     */
    private function parseContent(): void
    {
        // TODO handle stuff here
        $nextTwoTokens = $this->iterator->peek(0, 2);

        // Handle all double checks first (no need to check `]]` again)
        if ($nextTwoTokens === '[[') {
            throw new Exception('Nested [[');
        }
        if ($nextTwoTokens === '++') {
            $this->tokens[] = new Token(Keywords::ST_DOUBLE_PLUS_SIGN);
            $this->iterator->consumeNoReturn(2);
            return;
        }
        if (is_numeric($nextTwoTokens)) {
            // TODO iterate
            $this->tokens[] = new Token(Keywords::L_NUMBER, $nextTwoTokens);
            $this->iterator->consumeNoReturn(2);
            return;
        }

        $currentToken = $this->iterator->peek();

        if ($currentToken === '~') {
            $this->tokens[] = new Token(Keywords::ST_TILDE);
            $this->iterator->consumeNoReturn();
            return;
        }
        if ($currentToken === '=') {
            $this->tokens[] = new Token(Keywords::ST_EQUAL_SIGN);
            $this->iterator->consumeNoReturn();
            return;
        }
        if ($currentToken === '|') {
            $this->tokens[] = new Token(Keywords::ST_PIPE);
            $this->iterator->consumeNoReturn();
            return;
        }

        // TODO: WIP
        $this->tokens[] = new Token(Keywords::ST_PIPE, $currentToken);
        $this->iterator->consumeNoReturn();
    }

    /**
     * @throws Exception
     */
    private function parseStringLiteral(): void
    {
        $offset = 0;
        while ($this->iterator->peek($offset) !== PHP_EOL && $this->iterator->peek($offset, 2) !== '[[') {
            $offset++;
        }

        $text = $this->iterator->consume($offset);
        $this->tokens[] = new Token(Keywords::L_STRING, $text);
    }

    /**
     * @throws Exception
     */
    private function checkEOLOrPreChangeParsing(): bool
    {
        $current = $this->iterator->peek();
        if ($current === PHP_EOL) {
            $this->changeMode(static::MODE_END_OF_LINE);
            return true;
        }

        $nextTwoTokens = $this->iterator->peek(0, 2);

        // If mode is string literal, check if the next two tokens are [[
        if ($this->mode === static::MODE_STRING_LITERAL && $nextTwoTokens === '[[') {
            $this->tokens[] = new Token(Keywords::ST_DOUBLE_LEFT_BRACKET);
            $this->changeMode(static::MODE_PARSING_CONTENT);
            $this->iterator->consume(2);
            return true;
        }

        // If mode is parse, check if the next two tokens are ]]
        if ($this->mode === static::MODE_PARSING_CONTENT && $nextTwoTokens === ']]') {
            $this->tokens[] = new Token(Keywords::ST_DOUBLE_RIGHT_BRACKET);
            $this->changeMode(static::MODE_STRING_LITERAL);
            $this->iterator->consume(2);
            return true;
        }

        return false;
    }

    private function changeMode(string $mode): void
    {
        echo "Changing mode from: " . $this->mode . " to: " . $mode . PHP_EOL;
        echo "Iterator cursor at: " . $this->iterator->getCursorPosition() . PHP_EOL;
        var_dump($this->tokens);
        var_dump("===============================================");

        $this->mode = $mode;
    }
}
