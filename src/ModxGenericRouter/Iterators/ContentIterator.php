<?php

namespace OpimusCrime\ModxGenericRouter\Iterators;

class ContentIterator
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
        $this->contentLength = strlen($this->content);
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

        $current = substr($this->content, $this->index, 1);

        $this->index++;

        return $current;
    }
}
