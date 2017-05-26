<?php

namespace ModxGenericRouter\Tokens\Modx;

class Variable extends ModxToken
{
    const VARIABLE = 0;
    const PLACEHOLDER = 1;
    const SYSTEM_SETTING = 2;

    public function __toString()
    {
        $prefix = '*';
        if ($this->getData('type') === self::PLACEHOLDER) {
            $prefix = '+';
        }
        else if ($this->getData('type') === self::SYSTEM_SETTING) {
            $prefix = '++';
        }

        return '<' . $prefix . 'Variable: ' . $this->getNamePretty() . '>';
    }

    public function toArray()
    {
        $arr = parent::toArray();
        $arr['type'] = 'variable';

        return $arr;
    }
}
