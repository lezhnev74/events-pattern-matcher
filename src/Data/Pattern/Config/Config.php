<?php

namespace Lezhnev74\EventsPatternMatcher\Data\Pattern\Config;


class Config
{
    private $config;
    
    public function __construct(array $config)
    {
        $this->validateConfig($config);
        $this->config = $config;
    }
    
    private function validateConfig(array $config): void
    {
        // Make sure that given config describes acyclic graph
        try {
            $this->validateStructure($config);
        } catch (\Exception $e) {
            throw new BadConfig($e->getMessage());
        }
        if (!count($config['event_states'])) {
            throw new BadConfig("Config contains no events");
        }
    }
    
    private function validateStructure(array $config): void
    {
        //
        // All the config is described with this pattern
        //
        $pattern = [
            "event_states" => [
                "*" => [
                    "name"         => ":string min(1)",
                    "transitions?" => [
                        "*" => [
                            "to" => ":string min(1)",
                        ],
                    ],
                ],
            ],
        ];
        
        \matchmaker\catches($config, $pattern);
    }
    
}