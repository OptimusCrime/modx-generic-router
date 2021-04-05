<?php
namespace OptimusCrime\ModxGenericRouter\Parser\DSN;

use OptimusCrime\ModxGenericRouter\Tokens\BaseToken;

class UnParsedContext extends BaseContext
{
    private BaseToken $token;

    public function __construct(BaseToken $token)
    {
        $this->token = $token;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}
