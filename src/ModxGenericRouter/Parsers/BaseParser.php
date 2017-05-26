<?php

namespace ModxGenericRouter\Parsers;

use ModxGenericRouter\Log\Logger;
use ModxGenericRouter\Tokens\AmpersandToken;
use ModxGenericRouter\Tokens\AsteriskToken;
use ModxGenericRouter\Tokens\BacktickToken;
use ModxGenericRouter\Tokens\ColonToken;
use ModxGenericRouter\Tokens\EqualSignToken;
use ModxGenericRouter\Tokens\ExclamationPointToken;
use ModxGenericRouter\Tokens\Groups\TextToken;
use ModxGenericRouter\Tokens\Modx\Variable;
use ModxGenericRouter\Tokens\NewlineToken;
use ModxGenericRouter\Tokens\PlusSignToken;
use ModxGenericRouter\Tokens\QuestionMarkToken;
use ModxGenericRouter\Tokens\RegularToken;
use ModxGenericRouter\Tokens\WhiteSpaceToken;
use ModxGenericRouter\Tree\Node;
use ModxGenericRouter\Tree\NodeParser;

abstract class BaseParser
{
    private static function isValidNode(Node $node, $tokenClass = null)
    {
        if (count($node->getChildren()) === 0) {
            return false;
        }

        if ($tokenClass === null) {
            return true;
        }

        if (get_class($node->getChild(0)) !== $tokenClass) {
            return false;
        }

        return true;
    }

    public static function checkIfCached(Node $node)
    {
        if (!self::isValidNode($node, ExclamationPointToken::class)) {
            return false;
        }

        $node->removeChild(0);

        return true;
    }

    public static function getName(Node $node)
    {
        if (!self::isValidNode($node, TextToken::class)) {
            return null;
        }

        $name = $node->getChild(0);

        $node->removeChild(0);

        return $name;
    }

    public static function cleanContext(Node $node)
    {
        // We need to keep track of opened and closed backticks for property values so we don't accidentally
        // remove stuff from that.
        $backtickOpen = false;
        $newChildren = [];

        foreach ($node->getChildren() as $child) {
            if ($child instanceof BacktickToken) {
                // Simply toggle flag
                $backtickOpen = !$backtickOpen;
            }

            if ($backtickOpen or (!($child instanceof NewlineToken) and !($child instanceof WhiteSpaceToken))) {
                $newChildren[] = $child;
            }
        }

        $node->setChildren($newChildren);
    }

    public static function hasProperties(Node $node)
    {
        if (!self::isValidNode($node, QuestionMarkToken::class)) {
            return false;
        }

        $node->removeChild(0);

        return true;
    }

    const PROPERTY_AMPERSAND = 0;
    const PROPERTY_NAME = 1;
    const PROPERTY_EQUAL_SIGN = 2;
    const PROPERTY_BACKTICK_OPEN = 3;
    const PROPERTY_VALUE = 4;

    private static function newPropertyArray()
    {
        return [
            'name' => '',
            'value' => []
        ];
    }

    public static function parseProperties(Node $node)
    {
        // Parsing properties HAS to follow the following structure:
        // 1. AmpersandToken
        // 2. TextToken (or a RegularToken if the property only has one letter
        // 3. EqualSignToken
        // 4. BacktickToken
        // 5. ... (note: we may find another Node here, in which case we have to parse it)
        // 6. BacktickToken

        $properties = [];

        $status = self::PROPERTY_AMPERSAND;
        $current_property = self::newPropertyArray();

        foreach ($node->getChildren() as $child) {
            switch ($status) {
                case self::PROPERTY_AMPERSAND:
                    if ($child instanceof AmpersandToken) {
                        $status = self::PROPERTY_NAME;
                        continue;
                    }

                    Logger::getInstance()->addLine('Unexpected token while parsing properties. Expecting &,  found ' .
                    $child);

                    return $properties;
                case self::PROPERTY_NAME:
                    if ($child instanceof TextToken or $child instanceof RegularToken) {
                        $current_property['name'] = $child->getPretty();
                        $status = self::PROPERTY_EQUAL_SIGN;
                        continue;
                    }

                    Logger::getInstance()->addLine('Unexpected token while parsing properties.'.
                        ' Expecting property name, found ' .
                        $child);

                    return $properties;
                case self::PROPERTY_EQUAL_SIGN:
                    if ($child instanceof EqualSignToken) {
                        $status = self::PROPERTY_BACKTICK_OPEN;
                        continue;
                    }

                    Logger::getInstance()->addLine('Unexpected token while parsing properties.'.
                        ' Expecting equal sign, found ' .
                        $child);

                    return $properties;
                case self::PROPERTY_BACKTICK_OPEN:
                    if ($child instanceof BacktickToken) {
                        $status = self::PROPERTY_VALUE;
                        continue;
                    }

                    Logger::getInstance()->addLine('Unexpected token while parsing properties.' .
                        ' Expecting backtick, found ' .
                        $child);

                    return $properties;
                case self::PROPERTY_VALUE:
                    if ($child instanceof BacktickToken) {
                        // Closing the backtick and look for a new ampersand
                        $status = self::PROPERTY_AMPERSAND;
                        $properties[] = $current_property;
                        $current_property = self::newPropertyArray();
                        continue;
                    }

                    // Parsing nested Nodes
                    if ($child instanceof Node) {
                        $parsedNode = NodeParser::handleNode($child);

                        if ($parsedNode !== null) {
                            $current_property['value'][] = $parsedNode;
                        }

                        continue;
                    }

                    $current_property['value'][] = $child;
                    break;
                default:
                    Logger::getInstance()->addLine('Unexpected token while parsing properties. Found' . $child);

                    return $properties;
            }
        }

        return $properties;
    }

    public static function getVariableType(Node $node)
    {
        if (!self::isValidNode($node)) {
            return null;
        }

        $firstChild = $node->getChild(0);

        if ($firstChild instanceof AsteriskToken) {
            $node->removeChild(0);
            return Variable::VARIABLE;
        }

        if ($firstChild instanceof PlusSignToken) {
            $node->removeChild(0);

            // If the next token is ALSO a plus sign, it is a system setting type variable
            if (self::isValidNode($node, PlusSignToken::class)) {
                // We hav a system setting, remove the second plus too
                $node->removeChild(0);
                return Variable::SYSTEM_SETTING;
            }

            return Variable::PLACEHOLDER;
        }

        Logger::getInstance()->addLine('Unexpected token while parsing variable. Found' . $firstChild);

        return null;
    }

    public static function hasOutputFilters(Node $node)
    {
        foreach ($node->getChildren() as $child) {
            if ($child instanceof ColonToken) {
                return true;
            }
        }

        return false;
    }

    public static function cleanupPrefix(Node $node, $token)
    {
        if (!self::isValidNode($node, $token)) {
            return;
        }

        $node->removeChild(0);
    }

    public static function parseOutputFilters(Node $node)
    {
        //
    }
}
