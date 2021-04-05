<?php
namespace OptimusCrime\ModxGenericRouter\Parser\Tree;

use OptimusCrime\ModxGenericRouter\Parser\DSN\BaseContext;
use OptimusCrime\ModxGenericRouter\Parser\DSN\UnParsedContext;
use OptimusCrime\ModxGenericRouter\Tokens\BaseToken;

class ContextNode extends Node
{
    private ?BaseContext $context;

    /** @var ContextNode[]  */
    protected array $children;

    public function __construct()
    {
        parent::__construct();

        $this->context = null;
    }

    public function addContext(BaseToken $token): void
    {
        $this->context = new UnParsedContext($token);
        $this->closed = true;
    }

    public function getContext(): ?BaseContext
    {
        return $this->context;
    }
}
