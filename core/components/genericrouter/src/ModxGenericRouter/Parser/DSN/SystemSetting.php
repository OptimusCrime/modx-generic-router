<?php
namespace OptimusCrime\ModxGenericRouter\Parser\DSN;

class SystemSetting extends BaseContext
{
    private ?string $name;

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): ?string
    {
        return $this->name;
    }
}
