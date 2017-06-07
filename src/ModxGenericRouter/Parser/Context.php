<?php

namespace ModxGenericRouter\Parser;

use ModxGenericRouter\DSN\Fragment;
use ModxGenericRouter\Parser\Tree\Node;
use ModxGenericRouter\Tokens\EqualSignToken;
use ModxGenericRouter\Tokens\PlusSignToken;
use ModxGenericRouter\Tokens\RegularToken;
use ModxGenericRouter\Tokens\TildeToken;
use Symfony\Component\Config\Definition\Exception\Exception;


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

        // TODO check if we have a depth indication. This is indicated by a TildeToken followed by one (or more)
        // integers. 

        $fields = self::parseFields($node, $currentIndex);
        if (count($fields) > 0) {
            $fragment->addAllFields($fields);
        }

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

    private static function parseText(Node $node, $index)
    {
        $content = '';
        for ($i = $index; $i < count($node->getChildren()); $i++) {
            $currentChild = $node->getChild($i);
            if (!($currentChild instanceof RegularToken)) {
                break;
            }

            $content .= $currentChild->getValue();
        }

        return $content;
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
                $field = self::parseText($node, $currentIndex);
                if (mb_strlen($field) === 0) {
                    break;
                }

                $fields[] = $field;


                // Move cursor the length of the last field added + 1 (the PipeToken)
                $currentIndex += mb_strlen($fields[count($fields) - 1]) + 1;
            }
            catch (\Exception $e) {
                break;
            }
        }

        return $fields;
    }
}
