<?php
namespace OptimusCrime\ModxGenericRouter\Parser\Tree;

use Exception;

class Node
{
    protected bool $rootNode;

    /** @var Node[]  */
    protected array $children;
    protected bool $closed;

    public function __construct()
    {
        $this->rootNode = false;
        $this->children = [];

        // If token was provided, automatically close this Node
        $this->closed = false;
    }

    public function setRootNode(bool $flag): void
    {
        $this->rootNode = $flag;
    }

    public function isRootNode(): bool
    {
        return $this->rootNode;
    }

    /**
     * @param Node[] $children
     */
    public function setChildren(array $children): void
    {
        $this->children = $children;
    }

    public function addChild(Node $child): void
    {
        $this->children[] = $child;
    }

    public function hasChildren(): bool
    {
        return count($this->children) > 0;
    }

    public function hasChild($index): bool
    {
        return count($this->children) > $index;
    }

    /**
     * @param $index
     * @return Node
     * @throws Exception
     */
    public function getChild($index): Node
    {
        if (count($this->children) > $index) {
            return $this->children[$index];
        }
        throw new Exception('Children out of bounds');
    }

    public function removeChild(int $index): void
    {
        unset($this->children[$index]);
        // Make sure we have 0 -> n indexes
        $this->children = array_values($this->children);
    }

    public function setClosed(bool $flag): void
    {
        $this->closed = $flag;
    }

    public function isClosed(): bool
    {
        return $this->closed;
    }

    public function __toString(): string
    {
        if (count($this->children) === 0) {
            return '{null}';
        }

        return '{' . implode('', $this->children) . '}';
    }
}
