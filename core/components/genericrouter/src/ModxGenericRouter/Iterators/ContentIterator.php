<?php
namespace OptimusCrime\ModxGenericRouter\Iterators;

use Exception;

class ContentIterator
{
    private array $tokens;
    private int $contentLength;
    private int $index;

    public function __construct(string $content)
    {
        // TODO: Reuse array iterator?
        $this->tokens = str_split($content);
        $this->contentLength = count($this->tokens) - 1;
        $this->index = 0;
    }

    /**
     * @param int $offset
     * @return bool
     * @throws Exception
     */
    public function exists(int $offset = 0): bool
    {
        if ($this->contentLength === null) {
            throw new Exception('Iterating without content');
        }

        return $this->contentLength >= ($this->index + $offset);
    }

    /**
     * @param int $offset
     * @return bool
     * @throws Exception
     */
    public function hasNext(int $offset = 0): bool
    {
        return $this->exists($offset + 1);
    }

    /**
     * @param int $offset
     * @return string
     * @throws Exception
     */
    public function peek(int $offset = 0, int $length = 1): string
    {
        if (!$this->exists($offset)) {
            return PHP_EOL;
        }

        if ($length < 0) {
            throw new Exception('Invalid $length passed to peek. Was: ' . $length);
        }

        if ($length === 1) {
            return $this->peekOne($offset);
        }

        $output = [];
        for ($i = 0; $i < $length; $i++) {
            $output[] = $this->peekOne($offset + $i);
        }

        return implode('', $output);
    }

    private function peekOne(int $offset = 0) {
        return $this->tokens[$this->index + $offset];
    }

    /**
     * @param int $offset
     * @return string
     * @throws Exception
     */
    public function consume(int $offset = 1): string
    {
        if ($offset <= 0) {
            throw new Exception('Invalid $offset passed to consume. Was: ' . $offset);
        }

        $output = [];
        $limit = $this->index + $offset;
        while ($this->index < $limit) {
            $output[] = $this->tokens[$this->index];
            $this->index += 1;
        }

        return implode('', $output);
    }

    public function consumeNoReturn(int $offset = 1): void
    {
        $this->index += $offset;
    }

    public function getCursorPosition(): int
    {
        return $this->index;
    }
}
