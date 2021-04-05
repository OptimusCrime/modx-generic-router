<?php
namespace OptimusCrime\ModxGenericRouter\Parser\Tree;

use OptimusCrime\ModxGenericRouter\Tokens\BaseToken;

class TokenNode extends Node
{
    private ?BaseToken $token;

    /** @var TokenNode[]  */
    protected array $children;

    public function __construct(?BaseToken $token)
    {
        parent::__construct();

        $this->token = $token;

        // If token was provided, automatically close this Node
        $this->closed = $token !== null;
    }

    /**
     * @return TokenNode[]
     */
    public function getChildren(): array
    {
        return $this->children;
    }

    public function getToken(): ?BaseToken
    {
        return $this->token;
    }

    public function __toString(): string
    {
        if (count($this->children) === 0) {
            return $this->token->__toString();
        }

        if ($this->token === null) {
            return '{' . implode('', $this->children) . '}';
        }

        return '{' . $this->token->getValue() . ':' . implode('', $this->children) . '}';
    }
}
