<?php
namespace OptimusCrime\ModxGenericRouter\Tokens;

class Token
{
    private string $token;
    private ?string $value;

    public function __construct(string $token, ?string $value = null)
    {
        $this->token = $token;
        $this->value = $value;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }
}
