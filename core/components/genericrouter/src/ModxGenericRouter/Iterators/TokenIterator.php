<?php

namespace ModxGenericRouter\Iterators;

class TokenIterator
{
    private $tokens;
    private $contentLength;
    private $index;

    public function __construct(array $tokens)
    {
        $this->tokens = $tokens;
        $this->contentLength = count($this->tokens);
        $this->index = 0;
    }

    public function hasNext($offset = 0)
    {
        if ($this->contentLength === null) {
            throw new \Exception('Iterating without content');
        }

        return $this->contentLength >= ($this->index + $offset + 1);
    }

    public function goForward($steps = 1)
    {
        $this->index += $steps;
    }

    public function goBackward($steps)
    {
        $this->index -= $steps;

        if ($this->index < 0) {
            $this->index = 0;
        }
    }

    public function getNext()
    {
       return $this->get();
    }

    public function getPrevious()
    {
        return $this->get(-1);
    }

    public function get($offset = 0)
    {
        if (!$this->hasNext($offset)) {
            return null;
        }

        return $this->tokens[$this->index + $offset];
    }
}
