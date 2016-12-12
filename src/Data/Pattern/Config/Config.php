<?php

namespace Lezhnev74\EventsPatternMatcher\Data\Pattern\Config;


use Lezhnev74\EventsPatternMatcher\Data\Pattern\State\State;


/**
 * Class Config
 * Purpose is to validate that given JSON-array is a valid configuration for a Pattern
 *
 * @package Lezhnev74\EventsPatternMatcher\Data\Pattern\Config
 */
class Config
{
    private $config;
    private $reserved_event_names = ['__FINAL__', '__BEGIN__'];
    
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->validateConfig();
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
        $this->validateGraph();
        $this->addFinalAndEntryStates();
        $this->validateCompleteness();
    }
    
    private function addFinalAndEntryStates()
    {
        
        //
        // Add Final point ...---->FINAL
        //
        $this->config[] = [
            'name' => '__FINAL__',
        ];
        
        //
        // Add ENtry point ENTRY--->....
        //
        $this->config = array_merge(['name' => '__BEGIN__'], $this->config);
        
    }
    
    private function validateStructure(): void
    {
        //
        // All the config is described with this pattern
        //
        $pattern = [
            "*" => [
                "name"  => ":string min(1)",
                "ways?" => [
                    "*" => [
                        "then" => ":string min(1)",
                    ],
                ],
            ],
        ];
        
        \matchmaker\catches($this->config, $pattern);
    }
    
    /**
     * Validate contents of the graph
     */
    private function validateGraph()
    {
        //
        // Protect uniqueness of state names
        //
        $state_names = [];
        foreach ($this->config as $state) {
            if (!in_array($state['name'], $state_names)) {
                
                //
                // Make sure reserved words are not used
                //
                if (in_array($state['name'], $this->reserved_event_names)) {
                    throw new BadConfig("Event with reserved word found:" . $state['name']);
                }
                
                $state_names[] = $state['name'];
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
                if (!in_array($transition['then'], $state_names)) {
                    throw new BadConfig("State leads to unknown state: " . $transition['then']);
                }
            }
        }
        
        
    }
    
    private function validateCompleteness()
    {
        //
        // CESG (Complete ESG)
        //
        
        //
        // Make sure that each vertex is reachable from the input point
        //
        
        //
        // Make sure that each final point is reachable from any vertex
        //
    }
    
    /**
     * Create states and return those as array of Objects
     *
     * @return array
     */
    public function getStates(): array
    {
        $states = [];
        foreach ($this->config['states'] as $config_state) {
            //
            // Prepare state's transitions
            //
            $transitions = [];
            
            //
            //
            //
            $states[] = new State($config_state['name']);
        }
        
        return $states;
    }
    
}