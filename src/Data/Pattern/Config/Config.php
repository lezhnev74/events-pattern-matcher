<?php

namespace Lezhnev74\EventsPatternMatcher\Data\Pattern\Config;


/**
 * Class Config
 * Purpose is to validate that given JSON-array is a valid configuration for a Pattern
 *
 * TODO allow multiple vertexes to have the same event name (for complex patterns with many equal events)
 *
 * @package Lezhnev74\EventsPatternMatcher\Data\Pattern\Config
 */
class Config
{
    private $config;
    
    
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->validateConfig();
    }
    
    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }
    
    
    private function validateConfig(): void
    {
        // Make sure that given config describes acyclic graph
        try {
            $this->validateStructure();
        } catch (\Exception $e) {
            throw new BadConfig($e->getMessage());
        }
        if (!count($this->config)) {
            throw new BadConfig("Config contains no events");
        }
        
        // This is to make ESG => CESG ()
        $this->validateEventNames();
        
    }
    
    
    private function validateStructure(): void
    {
        //
        // All the config is described with this pattern
        //
        $pattern = [
            "*" => [
                "id"    => ":integer", // unique ID of the vertex
                "name"  => ":string min(1)", // event_name, can be duplicated in many vertexes
                "ways?" => [
                    "*" => [
                        "then" => ":integer",
                    ],
                ],
            ],
        ];
        
        \matchmaker\catches($this->config, $pattern);
    }
    
    /**
     * Validate contents of the graph
     */
    private function validateEventNames()
    {
        //
        // Collect all event names
        //
        $states = [];
        foreach ($this->config as $state) {
            if (!in_array($state['id'], $states)) {
                $states[] = $state['id'];
            }
        }
        
        //
        // Make sure all transitions lead to existing state
        //
        foreach ($this->config as $state) {
            if (!isset($state['ways'])) {
                continue;
            }
            foreach ($state['ways'] as $transition) {
                if (!in_array($transition['then'], $states)) {
                    throw new BadConfig("State leads to unknown state: " . $transition['then']);
                }
            }
        }
        
        
    }
    
    
}