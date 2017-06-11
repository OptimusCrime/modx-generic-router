<?php

namespace ModxGenericRouter\Parser;

use ModxGenericRouter\DSN\Field;
use ModxGenericRouter\DSN\Fragment;
use ModxGenericRouter\Parser\Tree\Node;
use ModxGenericRouter\Tokens\BaseToken;
use ModxGenericRouter\Tokens\DotToken;
use ModxGenericRouter\Tokens\EqualSignToken;
use ModxGenericRouter\Tokens\PipeToken;
use ModxGenericRouter\Tokens\PlusSignToken;
use ModxGenericRouter\Tokens\RegularToken;
use ModxGenericRouter\Tokens\TildeToken;
use ModxGenericRouter\Utilities\Formats;


class Context
{
    public static function parse(Node $rootNode)
    {
        $newChildren = [];
        foreach ($rootNode->getChildren() as $child) {
            if ($child instanceof Fragment) {
                $newChildren[] = $child;
                continue;
            }

            $newChildren[] = self::parseNode($child);
        }

        $rootNode->setChildren($newChildren);
    }

    private static function parseNode(Node $node)
    {
        // TODO: Rewrite this section. Create instance and use index as attribute instead

        $fragment = new Fragment();

        // If we have no children, return the empty fragment
        if (count($node->getChildren()) === 0) {
            return $fragment;
        }

        $currentIndex = 0;

        // Check if the current fragment is an URL. This is indicated by a TildeToken as the first child.
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

        // Add the content. This is whatever follows either the two PlusSignTokens or the TildeToken. This content is
        // either an integer if the fragment is an URL or it may be a system setting following the MODX system setting
        // validator rules.
        $fragment->addAllContent(self::parseText($node, $currentIndex));
        $currentIndex += mb_strlen($fragment->getContent());

        // Check if we have a depth indicator. This should be a TildeToken followed by an integer
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
            return $node->getChild($index) instanceof TildeToken;
        }
        catch (\Exception $e) {
            return false;
        }
    }

    private static function parseIsSystemSetting(Node $node, $index)
    {
        try {
            $firstChild = $node->getChild($index);
            $secondChild = $node->getChild($index + 1);

            return $firstChild instanceof PlusSignToken and $secondChild instanceof PlusSignToken;
        }
        catch (\Exception $e) {
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

        return true;
    }

    private static function parseDepth(Node $node, $index)
    {
        try {
            if (!($node->getChild($index) instanceof TildeToken)) {
                return null;
            }
        }
        catch (\Exception $e) {
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
            return $node->getChild($index) instanceof EqualSignToken;
        }
        catch(\Exception $e) {
            return false;
        }
    }

    private static function parseFieldContent(Node $node, $index) {
        $fields = [];
        $currentIndex = $index;
        while ($currentIndex < count($node->getChildren())) {
            try {
                $field = self::parseText($node, $currentIndex, [RegularToken::class, DotToken::class]);
                if (mb_strlen($field) === 0) {
                    break;
                }

                $fields[] = new Field($field);

                $currentIndex += mb_strlen($fields[count($fields) - 1]);

                // Check if we have a PipeToken
                if ($node->hasChild($currentIndex) and !($node->getChild($currentIndex) instanceof PipeToken)) {
                    break;
                }

                $currentIndex++;
            }
            catch (\Exception $e) {
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
