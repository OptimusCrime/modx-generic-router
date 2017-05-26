<?php

namespace ModxGenericRouter\Tokens\Modx;

class Comment extends ModxToken
{
    public function __toString()
    {
        return '<Comment: TODO>';
    }

    public function toArray()
    {
        $arr = parent::toArray();
        $arr['type'] = 'comment';

        return $arr;
    }
}
