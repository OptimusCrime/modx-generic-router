<?php

namespace ModxGenericRouter\Tree;

use ModxGenericRouter\Iterators\TokenIterator;
use ModxGenericRouter\Tokens\Groups\TagEndToken;
use ModxGenericRouter\Tokens\Groups\TagStartToken;

class Builder
{
    private $rootNode;
    private $iterator;

    public function __construct()
    {
        $this->rootNode = new Node();
        $this->rootNode->setRootNode(true);

        $this->iterator = new TokenIterator();
    }

    public function build(array $tokens)
    {
        $this->iterator->setContent($tokens);

        $this->buildNode($this->rootNode);
    }

    private function buildNode(Node $node)
    {
        while($this->iterator->exists()) {
            $current = $this->iterator->get();

            if ($current === null) {
                $this->iterator->goForward();

                continue;
            }

            switch (true) {
                case $current instanceof TagStartToken:
                    $newNode = new Node();
                    //$newNode->setParent($node);
                    $node->addChild($newNode);

                    $this->iterator->goForward();

                    $this->buildNode($newNode);
                    break;
                case $current instanceof TagEndToken:
                    $node->setClosed(true);
                    $this->iterator->goForward();
                    return;
                default:
                    $node->addChild($current);
                    $this->iterator->goForward();
            }
        }
    }

    public function getRootNode()
    {
        return $this->rootNode;
    }
}
