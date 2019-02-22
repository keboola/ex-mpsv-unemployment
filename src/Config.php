<?php

declare(strict_types=1);

namespace MyComponent;

use Keboola\Component\Config\BaseConfig;

class Config extends BaseConfig
{
    public function getUrl(): string
    {
        return 'https://portal.mpsv.cz/sz/stat/nz/mes';
    }

    public function getNumberOfFiles(): int
    {
        return (int) $this->getValue(['parameters', 'numberOfFiles']);
    }
}
