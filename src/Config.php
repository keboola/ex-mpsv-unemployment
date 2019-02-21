<?php

declare(strict_types=1);

namespace MyComponent;

use Keboola\Component\Config\BaseConfig;

class Config extends BaseConfig
{
    public function getUrl()
    {
        return 'https://portal.mpsv.cz/sz/stat/nz/mes';
    }
}
