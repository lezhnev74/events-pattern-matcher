<?php

namespace Lezhnev74\EventsPatternMatcher\Tests\Pattern;

use Fhaculty\Graph\Graph;
use Graphp\GraphViz\GraphViz;
use Lezhnev74\EventsPatternMatcher\Data\Pattern\BadPattern;
use Lezhnev74\EventsPatternMatcher\Data\Pattern\Config\Config;
use Lezhnev74\EventsPatternMatcher\Data\Pattern\Pattern;

class PatternTest extends \PHPUnit_Framework_TestCase
{
    function test_it_accepts_good_config()
    {
        
        // Set A
        list($pattern_config, $events) = PatternDataProvider::getSetA();
        
        $config  = new Config($pattern_config);
        $pattern = new Pattern($config);
        
        // Set B
        list($pattern_config, $events) = PatternDataProvider::getSetB();
        
        $config  = new Config($pattern_config);
        $pattern = new Pattern($config);
        
        // Debug: show the graph
        //$graphviz = new GraphViz();
        //var_dump($graphviz->createImageFile($pattern));
        //var_dump($graphviz->createImageFile($pattern));
        
    }
    
    function test_it_will_protect_from_many_components_in_graph()
    {
        $this->expectException(BadPattern::class);
        $pattern_config = [
            [
                "id"   => 1,
                "name" => "login",
            ],
            [
                "id"   => 2,
                "name" => "checkout",
            ],
        ];
        
        $config = new Config($pattern_config);
        new Pattern($config);
    }
    
    function test_it_accepts_loops_in_config()
    {
        $pattern_config = [
            [
                "id"   => 1,
                "name" => "login",
                "ways" => [
                    [
                        "then" => 2,
                    ],
                ],
            ],
            [
                "id"   => 2,
                "name" => "search",
                "ways" => [
                    [
                        "then" => 2,
                    ],
                    [
                        "then" => 3,
                    ],
                ],
            
            ],
            [
                "id"   => 3,
                "name" => "checkout",
            ],
        ];
        
        $config  = new Config($pattern_config);
        $pattern = new Pattern($config);
        
//        foreach ($pattern->getVertices() as $vertex) {
//            echo $pattern->getEventNameOfVertex($vertex) . " has edges_out: " . count($vertex->getEdgesOut()) . "\n";
//        }
        
//        $graph  = new Graph();
//        $vertex = $graph->createVertex();
    
//        echo $vertex->getEdgesOut()->getEdgesDistinct()->count(); // will give us 0
//        echo $vertex->getEdgesIn()->getEdgesDistinct()->count(); // will give us 0
//
//        $vertex->createEdgeTo($vertex);
//
//        echo $vertex->getEdgesOut()->getEdgesDistinct()->count(); // will give us 2
//        echo $vertex->getEdgesIn()->getEdgesDistinct()->count(); // will give us 2
        
        
        //$graphviz = new GraphViz();
        //var_dump($graphviz->createScript($pattern));
        //var_dump($graphviz->createImageFile($pattern));
        
        
    }
    
    
    public function test_config_is_correct() {
        $pattern_config = [
            [
                "id"   => 1,
                "name" => "login",
                "ways" => [
                    ["then" => 2],
                    ["then" => 3],
                ],
            ],
            [
                "id"   => 2,
                "name" => "search",
                "ways" => [
                    ["then" => 2],
                    ["then" => 3],
                ],
            ],
            [
                "id"   => 3,
                "name" => "checkout",
            ],
        ];
        $config         = new Config($pattern_config);
        $pattern        = new Pattern($config);
        
    }
}