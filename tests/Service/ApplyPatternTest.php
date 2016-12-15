<?php

namespace Lezhnev74\EventsPatternMatcher\Tests\Service;

use Graphp\GraphViz\GraphViz;
use Lezhnev74\EventsPatternMatcher\Data\Pattern\BadPattern;
use Lezhnev74\EventsPatternMatcher\Data\Pattern\Config\Config;
use Lezhnev74\EventsPatternMatcher\Data\Pattern\Pattern;
use Lezhnev74\EventsPatternMatcher\Data\Sequence\Sequence;
use Lezhnev74\EventsPatternMatcher\Service\ApplyPattern\ApplyPattern;
use Lezhnev74\EventsPatternMatcher\Service\ApplyPattern\ApplyPatternRequest;
use Lezhnev74\EventsPatternMatcher\Tests\Pattern\PatternDataProvider;

class ApplyPatternTest extends \PHPUnit_Framework_TestCase
{
    function test_service_validates_sequence()
    {
        
        // Set A
        list($pattern_config, $events) = PatternDataProvider::getSetA();
        
        $config   = new Config($pattern_config);
        $pattern  = new Pattern($config);
        $sequence = Sequence::fromArray($events);
        
        // call a service
        $request = new ApplyPatternRequest($pattern, $sequence);
        $service = new ApplyPattern($request);
        $report  = $service->execute();
        
        // make sure we get to the final point
        $this->assertTrue($report->isMatched());
        
    }
    
    function test_service_detects_sequence_which_does_not_match_the_pattern()
    {
        
        // Set A
        list($pattern_config, $events) = PatternDataProvider::getSetA_no_match();
        
        $config   = new Config($pattern_config);
        $pattern  = new Pattern($config);
        $sequence = Sequence::fromArray($events);
        
        // call a service
        $request = new ApplyPatternRequest($pattern, $sequence);
        $service = new ApplyPattern($request);
        $report  = $service->execute();
        
        // make sure we get to the final point
        $this->assertFalse($report->isMatched());
        
    }
    
    function test_service_matches_sequence_with_loops()
    {
        
        // Set A
        list($pattern_config, $events) = PatternDataProvider::getSetLoop();
        
        $config   = new Config($pattern_config);
        $pattern  = new Pattern($config);
        $sequence = Sequence::fromArray($events);
        
        // call a service
        $request = new ApplyPatternRequest($pattern, $sequence);
        $service = new ApplyPattern($request);
        $report  = $service->execute();
        
        // make sure we get to the final point
        $this->assertTrue($report->isMatched());
        
    }
    
    function test_service_apply_pattern_with_loops()
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
                        "then" => 3,
                    ],
                    [
                        "then" => 2,
                    ],
                ],
            
            ],
            [
                "id"   => 3,
                "name" => "checkout",
            ],
        ];
        
        $events = [
            ["name" => "A"],
            ["name" => "B"],
            ["name" => "login"],
            ["name" => "C"],
            ["name" => "search"],
            ["name" => "search"],
            ["name" => "login"],
            ["name" => "D"],
            ["name" => "D"],
            ["name" => "checkout"],
            ["name" => "E"],
            ["name" => "C"],
        ];
        
        $config   = new Config($pattern_config);
        $pattern  = new Pattern($config);
        $sequence = Sequence::fromArray($events);
        
        // call a service
        $request = new ApplyPatternRequest($pattern, $sequence);
        $service = new ApplyPattern($request);
        $report  = $service->execute();
        
        // make sure we get to the final point
        $this->assertTrue($report->isMatched());
        
        // Make sure Report tells me what pattern's vertexes was found in sequence
        $this->assertEquals(3, count($report->getMatchedEvents()));
        
        
    }
    
}