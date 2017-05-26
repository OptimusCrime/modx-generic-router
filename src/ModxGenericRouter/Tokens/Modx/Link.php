<?php

namespace ModxGenericRouter\Tokens\Modx;

class Link extends ModxToken
{
    public function __toString()
    {
        return '<' . ($this->cached ? '!' : '' ) . 'Link: ' . $this->getNamePretty() . '>';
    }

    public function toArray()
    {
        $arr = parent::toArray();
        $arr['type'] = 'link';

        return $arr;
    }
}
