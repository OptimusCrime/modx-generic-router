<?php

namespace ModxGenericRouter\Parser\Tree;


class Node
{
    private $rootNode;
    private $parent;
    private $children;
    private $closed;

    public function __construct()
    {
        $this->rootode = false;
        $this->parent = null;
        $this->children = [];
        $this->closed = false;
    }

    public function setRootNode($flag)
    {
        $this->rootNode = $flag;
    }

    public function isRootNode()
    {
        return $this->rootNode;
    }

    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setChildren($children)
    {
        $this->children = $children;
    }

    public function addChild($child)
    {
        $this->children[] = $child;
    }

    public function getChildren()
    {
        return $this->children;
    }

    public function hasChildren()
    {
        return count($this->children) > 0;
    }

    public function hasChild($index)
    {
        return count($this->children) > $index;
    }

    public function getChild($index)
    {
        if (count($this->children) > $index) {
            return $this->children[$index];
        }
        throw new \Exception('Children out of bounds');
    }

    public function removeChild($index)
    {
        unset($this->children[$index]);
        // Make sure we have 0 -> n indexes
        $this->children = array_values($this->children);
    }

    public function setClosed($flag)
    {
        $this->closed = $flag;
    }

    public function isClosed()
    {
        return $this->closed;
    }

    public function __toString()
    {
        if (count($this->children) === 0) {
            return '()';
        }

        return '(' .  implode(' ', $this->children) . ')';
    }
}
