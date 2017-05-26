<?php

namespace ModxGenericRouter\Tree;

use ModxGenericRouter\Utilities\ParentRelationship;

class Node extends ParentRelationship
{
    private $closed;

    public function __construct()
    {
        parent::__construct();

        $this->closed = false;
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
        return '<Node>';
    }

    public function toArray()
    {
        $childrenArray = [];
        foreach ($this->getChildren() as $child) {
            $childrenArray[] = $child->toArray();
        }

        return $childrenArray;
    }
}
