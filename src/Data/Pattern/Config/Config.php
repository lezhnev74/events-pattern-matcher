<?php

namespace Lezhnev74\EventsPatternMatcher\Data\Pattern\Config;


use Fhaculty\Graph\Graph;
use Fhaculty\Graph\Vertex;
use Graphp\Algorithms\ConnectedComponents;
use Graphp\Algorithms\ShortestPath\BreadthFirst;
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
    
    /**
     * @var Graph
     */
    private $graph;
    
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
        $this->validateEventNames();
        
        //
        // Now make a graph from the config
        //
        $this->makeGraph();
        $this->makeTrailingVertexes();
        
        //
        // Now make validation of the graph
        //
        $this->validateReachability();
        
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
    private function validateEventNames()
    {
        //
        // Collect all event names
        //
        $state_names = [];
        foreach ($this->config as $state) {
            if (!in_array($state['name'], $state_names)) {
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
    
    private function validateReachability()
    {
        //
        // Make sure all the vertexes are connected in single graph
        //
        $alg = new ConnectedComponents($this->graph);
        if (!$alg->isSingle()) {
            throw new BadConfig("Config has isolated components on the graph");
        }
        
        $entry_vertex = $this->getEntryVertex();
        $final_vertex = $this->getFinalVertex();
        
        
        //
        // Make sure that each vertex is reachable from the input point
        //
        $alg = new BreadthFirst($entry_vertex);
        foreach ($this->graph->getVertices() as $vertex) {
            if ($vertex->getId() == $final_vertex->getId() || $vertex->getId() == $entry_vertex->getId()) {
                // skip final vertex from analysis
                continue;
            }
            
            if (!$alg->getDistance($vertex)) {
                throw new BadConfig("Event[" . $vertex->getAttribute('event_name') . "] has no path from Entry point");
            }
        }
        
        
        //
        // Make sure that each final point is reachable from any vertex
        //
        
        foreach ($this->graph->getVertices() as $vertex) {
            if ($vertex->getId() == $final_vertex->getId() || $vertex->getId() == $entry_vertex->getId()) {
                // skip final vertex from analysis
                continue;
            }
            $alg = new BreadthFirst($vertex);
            if (!$alg->getDistance($final_vertex)) {
                throw new BadConfig("Event[" . $vertex->getAttribute('event_name') . "] has no path to Final point");
            }
        }
    }
    
    
    /**
     * Will make up a graph based on Config data
     * Currently used a graph from https://github.com/graphp/algorithms
     */
    private function makeGraph()
    {
        $this->graph = new Graph();
        
        //
        // Make all vertexes
        //
        foreach ($this->config as $item) {
            $vertex = $this->graph->createVertex();
            $vertex->setAttribute('event_name', $item['name']);
        }
        
        //
        // Make all edges
        //
        foreach ($this->config as $item) {
            
            $vertex = $this->graph->getVertices()->getVertexMatch(function (Vertex $vertex) use ($item) {
                return $vertex->getAttribute('event_name') == $item['name'];
            });
            
            if (isset($item['ways'])) {
                foreach ($item['ways'] as $way) {
                    // find existing vertex by it's event name
                    $target_vertex = $this->graph->getVertices()->getVertexMatch(function (Vertex $vertex) use ($way) {
                        return $vertex->getAttribute('event_name') == $way['then'];
                    });
                    // attach a directed edge between them
                    $vertex->createEdgeTo($target_vertex);
                }
            }
        }
        
    }
    
    function makeTrailingVertexes()
    {
        //
        // Add entry vertex
        //
        $entry_points = [];
        // find all vertexes with no incoming edges
        foreach ($this->graph->getVertices() as $vertex) {
            if (!$vertex->getEdgesIn()->count()) {
                $entry_points[] = $vertex;
            }
        }
        // Make input vertex
        $entry_vertex = $this->graph->createVertex();
        $entry_vertex->setAttribute('__begin__', true);
        // Link entry vertex with each vertex which had no incomings
        foreach ($entry_points as $vertex) {
            $entry_vertex->createEdgeTo($vertex);
        }
        
        //
        // Add out vertex
        //
        $final_points = [];
        // find all vertexes with no incoming edges
        foreach ($this->graph->getVertices() as $vertex) {
            if (!$vertex->getEdgesOut()->count()) {
                $final_points[] = $vertex;
            }
        }
        // Make input vertex
        $final_vertex = $this->graph->createVertex();
        $final_vertex->setAttribute('__final__', true);
        // Link entry vertex with each vertex which had no incomings
        foreach ($final_points as $vertex) {
            $vertex->createEdgeTo($final_vertex);
        }
    }
    
    /**
     * Get entry vertex
     *
     * @return Vertex|null
     */
    function getEntryVertex()
    {
        $vertex = $this->graph->getVertices()->getVertexMatch(function (Vertex $vertex) {
            return $vertex->getAttribute('__begin__') === true;
        });
        
        return $vertex;
    }
    
    /**
     * Get final vertex
     *
     * @return Vertex|null
     */
    function getFinalVertex()
    {
        $vertex = $this->graph->getVertices()->getVertexMatch(function (Vertex $vertex) {
            return $vertex->getAttribute('__final__') === true;
        });
        
        return $vertex;
    }
    
    
    /**
     * @return mixed
     */
    public function getGraph()
    {
        return $this->graph;
    }
    
    
}