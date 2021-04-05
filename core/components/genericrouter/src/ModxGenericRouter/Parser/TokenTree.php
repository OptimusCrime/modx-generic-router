<?php
namespace OptimusCrime\ModxGenericRouter\Parser;

use Exception;

use OptimusCrime\ModxGenericRouter\Iterators\GenericArrayIterator;
use OptimusCrime\ModxGenericRouter\Parser\Tree\TokenNode;
use OptimusCrime\ModxGenericRouter\Tokens\Groups\TagEnd;
use OptimusCrime\ModxGenericRouter\Tokens\Groups\TagStart;
use OptimusCrime\ModxGenericRouter\Tokens\TokenCollection;

class TokenTree
{
    private TokenNode $rootNode;

    /**
     * Tree constructor.
     * @param TokenCollection $tokenCollection
     * @throws Exception
     */
    public function __construct(TokenCollection $tokenCollection)
    {
        $rootNode = new TokenNode(null);
        $rootNode->setRootNode(true);
        $rootNode->setClosed(true);

        $this->rootNode = $rootNode;

        $iterator = new GenericArrayIterator($tokenCollection->getTokens());
        self::build($this->rootNode, $iterator);
    }

    public function getRootNode(): TokenNode
    {
        return $this->rootNode;
    }

    /**
     * @param TokenNode $node
     * @param GenericArrayIterator $iterator
     * @throws Exception
     */
    private function build(TokenNode $node, GenericArrayIterator $iterator)
    {
        while ($iterator->hasNext()) {
            $current = $iterator->getCurrent();

            // If the current token is not a tag, just add it as a child and continue
            if (!($current instanceof TagEnd) and !($current instanceof TagStart)) {
                $node->addChild(new TokenNode($current));
                $iterator->goForwards();
                continue;
            }

            // We found a start tag. Create a new node, set relationships and recursion
            if ($current instanceof TagStart) {
                $newNode = new TokenNode(null);
                $node->addChild($newNode);

                $iterator->goForwards();

                self::build($newNode, $iterator);
                continue;
            }

            // We found an end tag. Close the current node and jump out
            $node->setClosed(true);
            $iterator->goForwards();
            return;
        }
    }

    public function __toString(): string
    {
        return $this->rootNode->__toString();
    }
}
