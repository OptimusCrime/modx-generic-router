<?php

namespace ModxGenericRouter\Tokens\Groups;

class TagStartToken
{
    public function __toString()
    {
        return '<[[>';
    }
}
