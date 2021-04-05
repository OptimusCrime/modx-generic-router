<?php
namespace OptimusCrime\ModxGenericRouter\Iterators;

use Exception;

class GenericArrayIterator
{
    /** @var array */
    private array $tokens;
    private int $contentLength;
    private int $index;

    /**
     * TokenIterator constructor.
     * @param array $tokens
     */
    public function __construct(array $tokens)
    {
        $this->tokens = $tokens;
        $this->contentLength = count($this->tokens);
        $this->index = 0;
    }

    /**
     * @param int $offset
     * @return bool
     * @throws Exception
     */
    public function hasNext(int $offset = 0): bool
    {
        if ($this->contentLength === null) {
            throw new Exception('Iterating without content');
        }

        return $this->contentLength >= ($this->index + $offset + 1);
    }

    public function goForwards(int $steps = 1): void
    {
        $this->index += $steps;
    }

    public function goBackwards(int $steps): void
    {
        $this->index -= $steps;

        if ($this->index < 0) {
            $this->index = 0;
        }
    }

    /**
     * @returns mixed
     * @throws Exception
     */
    public function getCurrent()
    {
       return $this->get();
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getPrevious()
    {
        return $this->get(-1);
    }

    /**
     * @param int $offset
     * @return mixed
     * @throws Exception
     */
    public function get(int $offset = 0)
    {
        if (!$this->hasNext($offset)) {
            throw new Exception("Does not have any more tokens");
        }

        return $this->tokens[$this->index + $offset];
    }
}
