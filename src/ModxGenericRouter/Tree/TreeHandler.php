<?php

namespace ModxGenericRouter\Tree;

class TreeHandler
{
    private $rootNode;

    public function __construct()
    {
        $this->rootNode = null;
    }

    public function run($tokens)
    {
        $builder = new Builder();
        $builder->build($tokens);

        $this->rootNode = NodeParser::parse($builder->getRootNode());
    }

    public function getRootNode()
    {
        return $this->rootNode;
    }
}
