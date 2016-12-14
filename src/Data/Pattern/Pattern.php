<?php
namespace Lezhnev74\EventsPatternMatcher\Data\Pattern;

use Fhaculty\Graph\Graph;
use Fhaculty\Graph\Vertex;
use Graphp\Algorithms\ConnectedComponents;
use Graphp\Algorithms\ShortestPath\BreadthFirst;
use Lezhnev74\EventsPatternMatcher\Data\Pattern\Config\Config;

class Pattern extends Graph
{
    /**
     * @var Config
     */
    private $config;
    
    /**
     * Pattern constructor.
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        parent::__construct();
        
        //
        // Prepare pattern's graph
        //
        $this->config = $config;
        $this->initFromConfig();
        // Make sure it has single component
        $this->validateConnectedness();
        // Add Entry\Final vertexes
        $this->makeTrailingVertexes();
        // Make sure each vertex is reachable from entry point and to final point
        $this->validateReachability();
    }
    
    /**
     * Will make up a graph based on Config data
     * Currently used a graph from https://github.com/graphp/algorithms
     */
    private function initFromConfig()
    {
        //
        // Make all vertexes
        //
        foreach ($this->config->getConfig() as $item) {
            $vertex = $this->createVertex();
            $vertex->setAttribute('event_name', $item['name']);
        }
        
        //
        // Make all edges
        //
        foreach ($this->config->getConfig() as $item) {
            
            $vertex = $this->getVertices()->getVertexMatch(function (Vertex $vertex) use ($item) {
                return $vertex->getAttribute('event_name') == $item['name'];
            });
            
            if (isset($item['ways'])) {
                foreach ($item['ways'] as $way) {
                    // find existing vertex by it's event name
                    $target_vertex = $this->getVertices()->getVertexMatch(function (Vertex $vertex) use ($way) {
                        return $vertex->getAttribute('event_name') == $way['then'];
                    });
                    // attach a directed edge between them
                    $vertex->createEdgeTo($target_vertex);
                }
            }
        }
        
    }
    
    
    /**
     * Graph must not have isolated components like a-->b and c-->d
     */
    private function validateConnectedness()
    {
        //
        // Make sure all the vertexes are connected in single graph
        //
        $alg = new ConnectedComponents($this);
        if (!$alg->isSingle()) {
            throw new BadPattern("Graph has isolated components");
        }
    }
    
    /**
     * Make sure each component is reachable from the entry point,
     * and final point is reachable from every point
     */
    private function validateReachability()
    {
        $entry_vertex = $this->getEntryVertex();
        $final_vertex = $this->getFinalVertex();
        
        //
        // Make sure that each vertex is reachable from the input point
        //
        $alg = new BreadthFirst($entry_vertex);
        foreach ($this->getVertices() as $vertex) {
            if ($vertex->getId() == $final_vertex->getId() || $vertex->getId() == $entry_vertex->getId()) {
                // skip final vertex from analysis
                continue;
            }
            
            if (!$alg->getDistance($vertex)) {
                throw new BadPattern("Event[" . $vertex->getAttribute('event_name') . "] has no path from Entry point");
            }
        }
        
        //
        // Make sure that each final point is reachable from any vertex
        //
        foreach ($this->getVertices() as $vertex) {
            if ($vertex->getId() == $final_vertex->getId() || $vertex->getId() == $entry_vertex->getId()) {
                // skip final vertex from analysis
                continue;
            }
            $alg = new BreadthFirst($vertex);
            if (!$alg->getDistance($final_vertex)) {
                throw new BadPattern("Event[" . $vertex->getAttribute('event_name') . "] has no path to Final point");
            }
        }
    }
    
    /**
     * - Add entry point so all vertexes with no incoming edges will be linked from it
     * - Add final point so all vertexes with no outcoming edges will be link to this one
     */
    function makeTrailingVertexes()
    {
        //
        // Add entry vertex
        //
        $entry_points = [];
        // find all vertexes with no incoming edges
        foreach ($this->getVertices() as $vertex) {
            if (!$vertex->getEdgesIn()->count()) {
                $entry_points[] = $vertex;
            }
        }
        // Make input vertex
        $entry_vertex = $this->createVertex();
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
        foreach ($this->getVertices() as $vertex) {
            if (!$vertex->getEdgesOut()->count()) {
                $final_points[] = $vertex;
            }
        }
        // Make input vertex
        $final_vertex = $this->createVertex();
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
        $vertex = $this->getVertices()->getVertexMatch(function (Vertex $vertex) {
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
        $vertex = $this->getVertices()->getVertexMatch(function (Vertex $vertex) {
            return $vertex->getAttribute('__final__') === true;
        });
        
        return $vertex;
    }
        
}