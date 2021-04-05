<?php
namespace OptimusCrime\ModxGenericRouter\Parser;

use Exception;
use OptimusCrime\ModxGenericRouter\DSN\Field;
use OptimusCrime\ModxGenericRouter\DSN\Fragment;
use OptimusCrime\ModxGenericRouter\Iterators\GenericArrayIterator;
use OptimusCrime\ModxGenericRouter\Parser\DSN\SystemSetting;
use OptimusCrime\ModxGenericRouter\Parser\DSN\UnParsedContext;
use OptimusCrime\ModxGenericRouter\Parser\Tree\ContextNode;
use OptimusCrime\ModxGenericRouter\Parser\Tree\Node;
use OptimusCrime\ModxGenericRouter\Tokens\BaseToken;
use OptimusCrime\ModxGenericRouter\Tokens\EqualSign;
use OptimusCrime\ModxGenericRouter\Tokens\Pipe;
use OptimusCrime\ModxGenericRouter\Tokens\PlusSign;
use OptimusCrime\ModxGenericRouter\Tokens\RegularToken;
use OptimusCrime\ModxGenericRouter\Tokens\Tilde;
use OptimusCrime\ModxGenericRouter\Utilities\Formats;


class Context
{
    private ContextNode $rootNode;
    private bool $dirty;

    public function __construct(ContextNode $rootNode)
    {
        $this->rootNode = $rootNode;
        $this->dirty = false;
    }

    public function startParse(): bool
    {
        $this->dirty = false;

        $this->parse($this->rootNode);

        return $this->dirty;
    }

    /**
     * @param ContextNode $node
     * @throws Exception
     */
    private function parse(ContextNode $node): ContextNode
    {
        $iterator = new GenericArrayIterator($node->getChildren());
        $newChildren = [];
        while ($iterator->hasNext()) {
            $currentNode = $iterator->getCurrent();

            // Not two more nodes, break early in this iteration
            if (!$iterator->hasNext(1)) {
                $iterator->goForwards();
                continue;
            }

            $nextNode = $iterator->get(1);

            if ($currentNode instanceof UnParsedContext && $nextNode instanceof UnParsedContext) {
                $currentToken = $currentNode->getToken();
                $nextToken = $nextNode->getToken();

                // Handle ++ -> SystemSetting
                if ($currentToken instanceof PlusSign && $nextToken instanceof PlusSign) {
                    $newChildren[] = new SystemSetting();
                    $iterator->goForwards(2);
                    continue;
                }

                // Handle ~ -> Link

            }
        }

        $node->setChildren($newChildren);

        return $node;
    }

    private function parseNode(Node $node)
    {
        // TODO: Rewrite this section. Create instance and use index as attribute instead

        $fragment = new Fragment();

        // If we have no children, return the empty fragment
        if (count($node->getChildren()) === 0) {
            return $fragment;
        }

        $currentIndex = 0;

        // Check if the current fragment is an URL. This is indicated by a Tilde as the first child.
        $fragment->setUrl(self::parseIsUrl($node, $currentIndex));
        if ($fragment->isUrl()) {
            $currentIndex++;
        }

        // Check if the current fragment is using the value of a system setting. This is indicated by two consecutive
        // PlusSignTokens. Note that it is possible to have a link that uses an integer system setting value too, which
        // is why we can either check this as index starting at either 0 or 1.
        $fragment->setSystemSetting(self::parseIsSystemSetting($node, $currentIndex));
        if ($fragment->isSystemSetting()) {
            $currentIndex += 2;
        }

        // Add the content. This is whatever follows either the two PlusSignTokens or the Tilde. This content is
        // either an integer if the fragment is an URL or it may be a system setting following the MODX system setting
        // validator rules.
        $fragment->addAllContent(self::parseText($node, $currentIndex));
        $currentIndex += mb_strlen($fragment->getContent());

        // Check if we have a depth indicator. This should be a Tilde followed by an integer
        $fragment->setDepth(self::parseDepth($node, $currentIndex));
        if ($fragment->getDepth() > 0) {
            $currentIndex += 1 + strlen(((string) $fragment->getDepth()));
        }


        $fields = self::parseFields($node, $currentIndex);
        if (count($fields) > 0) {
            $fragment->addAllFields($fields);
        }

        // Cleanup / Sanitycheck
        self::cleanupFragment($fragment);

        return $fragment;
    }

    private static function parseIsUrl(Node $node, $index)
    {
        try {
            return $node->getChild($index) instanceof Tilde;
        }
        catch (Exception $e) {
            return false;
        }
    }

    private static function parseIsSystemSetting(Node $node, $index)
    {
        try {
            $firstChild = $node->getChild($index);
            $secondChild = $node->getChild($index + 1);

            return $firstChild instanceof PlusSign and $secondChild instanceof PlusSign;
        }
        catch (Exception $e) {
            return false;
        }
    }

    private static function parseText(
        Node $node,
        $index,
        array $allowedTokens=[RegularToken::class],
        array $regularConstraints=[]
    )
    {
        $content = '';
        for ($i = $index; $i < count($node->getChildren()); $i++) {
            $currentChild = $node->getChild($i);
            if (self::mismatchToken($currentChild, $allowedTokens, $regularConstraints)) {
                break;
            }

            $content .= $currentChild->getValue();
        }

        return $content;
    }

    private static function mismatchToken(BaseToken $token, array $allowedTokens, array $allowedConstraints)
    {
        foreach ($allowedTokens as $allowedToken) {
            if ($token instanceof $allowedToken) {
                // If the current Node is instance of RegularToken we may have supplied additional constraints that
                // we should evaluate
                if ($token instanceof RegularToken and count($allowedConstraints) > 0) {
                    return self::mismatchRegularConstraints($token, $allowedConstraints);
                }

                return false;
            }
        }

        return true;
    }

    private static function mismatchRegularConstraints(RegularToken $token, array $constraints)
    {
        return false;
        /*
        foreach ($constraints as $constraint) {
            switch($constraint) {
                case RegularToken::INTEGER:
                    if (!$token->isInteger()) {
                        return false;
                    }
                    break;
                case RegularToken::ALPHA:
                    if (!$token->isAlpha()) {
                        return false;
                    }
                    break;
                default:
                    return false;
                    break;
            }
        }
        */

        return true;
    }

    private static function parseDepth(Node $node, $index)
    {
        try {
            if (!($node->getChild($index) instanceof Tilde)) {
                return null;
            }
        }
        catch (Exception $e) {
            return null;
        }

        $depth = self::parseText($node, $index + 1, [RegularToken::class], [RegularToken::INTEGER]);
        if (strlen($depth) == 0) {
            return null;
        }

        return (int) $depth;
    }

    private static function parseFields(Node $node, $index)
    {
        if (!self::parseHasFields($node, $index)) {
            return [];
        }

        return self::parseFieldContent($node, $index + 1);
    }

    private static function parseHasFields(Node $node, $index)
    {
        try {
            return $node->getChild($index) instanceof EqualSign;
        }
        catch(Exception $e) {
            return false;
        }
    }

    private static function parseFieldContent(Node $node, $index) {
        $fields = [];
        $currentIndex = $index;
        while ($currentIndex < count($node->getChildren())) {
            try {
                $field = self::parseText($node, $currentIndex, [RegularToken::class, Dot::class]);
                if (mb_strlen($field) === 0) {
                    break;
                }

                $fields[] = new Field($field);

                $currentIndex += mb_strlen($fields[count($fields) - 1]);

                // Check if we have a Pipe
                if ($node->hasChild($currentIndex) and !($node->getChild($currentIndex) instanceof Pipe)) {
                    break;
                }

                $currentIndex++;
            }
            catch (Exception $e) {
                break;
            }
        }

        return $fields;
    }

    private static function cleanupFragment(Fragment $fragment)
    {
        // Check if we should convert to ID
        self::cleanupFragmentId($fragment);
    }

    private static function cleanupFragmentId(Fragment $fragment)
    {
        if ($fragment->isSystemSetting()) {
            return;
        }

        if (!Formats::isInteger($fragment->getContent())) {
            return;
        }

        $fragment->setId(true);
        $fragment->setContent((int) $fragment->getContent());
    }
}
