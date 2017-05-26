<?php

namespace ModxGenericRouter\Iterators;

class TokenIterator
{
    private $content;
    private $contentLength;
    private $index;

    public function __construct()
    {
        $this->content = null;
        $this->contentLength = null;
        $this->index = 0;
    }

    public function setContent($content)
    {
        $this->content = $content;
        $this->contentLength = count($this->content);
    }

    public function exists($offset = 0)
    {
        if ($this->contentLength === null) {
            throw new \Exception('Iterating without content');
        }

        return $this->contentLength >= ($this->index + $offset - 1);
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

    public function getCurrent()
    {
        return $this->get();
    }

    public function getNext()
    {
       return $this->get(1);
    }

    public function getPrevious()
    {
        return $this->get(-1);
    }

    public function get($offset = 0)
    {
        if (!$this->exists($offset)) {
            return null;
        }

        return $this->content[$this->index + $offset];
    }
}
