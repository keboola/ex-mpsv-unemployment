<?php

declare(strict_types=1);

namespace MyComponent;

use Keboola\Component\BaseComponent;

class Component extends BaseComponent
{
    public function run(): void
    {
        echo "Returning data";
    }

    protected function getConfigClass(): string
    {
        return Config::class;
    }

    protected function getConfigDefinitionClass(): string
    {
        return ConfigDefinition::class;
    }
}