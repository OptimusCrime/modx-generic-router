<?php
namespace OptimusCrime\ModxGenericRouter\Parser;

use Exception;
use OptimusCrime\ModxGenericRouter\Iterators\GenericArrayIterator;
use OptimusCrime\ModxGenericRouter\Parser\Tree\ContextNode;
use OptimusCrime\ModxGenericRouter\Parser\Tree\Node;
use OptimusCrime\ModxGenericRouter\Parser\Tree\TokenNode;
use OptimusCrime\ModxGenericRouter\Tokens\EmptyToken;
use OptimusCrime\ModxGenericRouter\Tokens\Groups\TagEnd;
use OptimusCrime\ModxGenericRouter\Tokens\Groups\TagStart;
use OptimusCrime\ModxGenericRouter\Tokens\Groups\Text;
use OptimusCrime\ModxGenericRouter\Tokens\LeftBracket;
use OptimusCrime\ModxGenericRouter\Tokens\RegularToken;
use OptimusCrime\ModxGenericRouter\Tokens\RightBracket;
use OptimusCrime\ModxGenericRouter\Tokens\TokenCollection;

class Parser
{
    /**
     * @param TokenCollection $tokenCollection
     * @throws Exception
     */
    public function run(TokenCollection $tokenCollection): void
    {
        // First we make sure to concatenate every [[ and ]] into their own group tokens
        $tokens = $this->concatenate($tokenCollection);

        // Turn into a tree of tokens
        $tokenTree = new TokenTree($tokens);

        var_dump((string) $tokenTree);

        // Take the root TokenNode and parse the entire tree into a ContextTree, setting all
        // new nodes as UnParsedContext nodes for now
        //$contextNode = $this->parseFoobar($tokenTree->getRootNode());
        //var_dump($contextNode);

        // Parse the tree contextually
        //$context = new Context($contextNode);
        //$context->startParse();

    }

    private function parseFoobar(TokenNode $node): ContextNode
    {
        $contextNode = new ContextNode();
        $contextNode->setRootNode($node->isRootNode());

        if ($node->getToken() !== null) {
            // Lol, wtf
            $contextNode->addContext($node->getToken());
        }

        if ($node->hasChildren()) {
            $newChildren = [];
            foreach ($node->getChildren() as $child) {
                $newChildren[] = $this->parseFoobar($child);
            }

            $contextNode->setChildren($newChildren);
        }

        return $contextNode;
    }

    /**
     * @param TokenCollection $tokenCollection
     * @return TokenCollection
     * @throws Exception
     */
    private function concatenate(TokenCollection $tokenCollection): TokenCollection
    {
        $newTokenCollection = new TokenCollection();
        $iterator = new GenericArrayIterator($tokenCollection->getTokens());
        while ($iterator->hasNext()) {
            $currentToken = $iterator->getCurrent();

            // Handle groups of text
            if ($currentToken instanceof RegularToken) {
                $text = [$currentToken->getValue()];
                while ($iterator->hasNext(count($text))) {
                    $offsetToken = $iterator->get(count($text));
                    if ($offsetToken instanceof RegularToken) {
                        $text[] = $offsetToken->getValue();
                    }
                    else {
                        break;
                    }
                }

                $newTokenCollection->addToken(new Text(implode('', $text)));
                $iterator->goForwards(count($text));
                continue;
            }

            // The current token is not a bracket
            if (!($currentToken instanceof LeftBracket) && !($currentToken instanceof RightBracket)) {
                $newTokenCollection->addToken($currentToken);
                $iterator->goForwards();
                continue;
            }

            // Current is either left or right bracket, if we have no more tokens,
            // it can not be a group
            if (!$iterator->hasNext()) {
                $newTokenCollection->addToken($currentToken);
                $iterator->goForwards();
                continue;
            }

            // Current is either left or right bracket, and we have more tokens
            $nextToken = $iterator->get(1);
            if ($currentToken instanceof LeftBracket && $nextToken instanceof LeftBracket) {
                $newTokenCollection->addToken(new TagStart());
                $iterator->goForwards(2);
                continue;
            }
            if ($currentToken instanceof RightBracket && $nextToken instanceof RightBracket) {
                $newTokenCollection->addToken(new TagEnd());
                $iterator->goForwards(2);
                continue;
            }

            // We have no matching brackets after each other, just add one and then the other
            $newTokenCollection->addToken($currentToken);
            $iterator->goForwards();
        }

        return $newTokenCollection;
    }

    private function contextualize(Node $node): void
    {
        $token = $node->getToken();

        // Special handling of start node
        if ($token instanceof EmptyToken) {
            var_dump($node->getChildren());
        }

    }
}
