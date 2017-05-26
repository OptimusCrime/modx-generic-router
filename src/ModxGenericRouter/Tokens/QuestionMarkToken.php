<?php

namespace ModxGenericRouter\Tokens;

class QuestionMarkToken extends BaseToken
{
    public static $TOKEN = ['?'];

    public function __toString()
    {
        return '<?>';
    }
}
