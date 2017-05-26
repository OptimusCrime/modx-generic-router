<?php

namespace ModxGenericRouter\Iterators;

class ContentIterator
{
    private $content;
    private $contentLength;
    private $index;

    public function __construct($content)
    {
        $this->content = $content;
        $this->contentLength = mb_strlen($this->content);
        $this->index = 0;
    }

    public function hasNext()
    {
        if ($this->contentLength === null) {
            throw new \Exception('Iterating without content');
        }

        return $this->contentLength >= $this->index;
    }

    public function getNext()
    {
        if (!$this->hasNext()) {
            throw new \Exception('Iterator beyond content');
        }

        $current = mb_substr($this->content, $this->index, 1);

        $this->index++;

        return $current;
    }
}
