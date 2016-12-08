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
    }
    
    private function validateStructure(array $config): void
    {
        $pattern = [
            "event_states" => [
                "*" => [
                    "name"        => ":string min(1)",
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