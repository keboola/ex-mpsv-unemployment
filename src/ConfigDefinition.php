<?php

declare(strict_types=1);

namespace ExtractorMpsv;

use Keboola\Component\Config\BaseConfigDefinition;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

class ConfigDefinition extends BaseConfigDefinition
{
    protected function getParametersDefinition(): ArrayNodeDefinition
    {
        $parametersNode = parent::getParametersDefinition();
        // @formatter:off
        /** @noinspection NullPointerExceptionInspection */
        $parametersNode
            ->children()
                ->integerNode('fromMonth')
                    ->isRequired()
                ->end()
                ->integerNode('fromYear')
                    ->isRequired()
                ->end()
                ->integerNode('toMonth')
                    ->isRequired()
                ->end()
                ->integerNode('toYear')
                    ->isRequired()
                ->end()
            ->end()
        ;
        // @formatter:on
        return $parametersNode;
    }
}
