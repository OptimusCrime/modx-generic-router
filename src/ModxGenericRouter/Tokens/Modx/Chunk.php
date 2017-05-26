<?php

namespace ModxGenericRouter\Tokens\Modx;

class Chunk extends ModxToken
{
    public function __toString()
    {
        return '<' . ($this->cached ? '!' : '' ) . 'Chunk: ' . $this->getNamePretty() . '>';
    }

    public function toArray()
    {
        $arr = parent::toArray();
        $arr['type'] = 'chunk';

        return $arr;
    }
}
