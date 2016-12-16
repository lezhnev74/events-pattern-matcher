<?php

namespace Lezhnev74\EventsPatternMatcher\Tests\Service;

use Carbon\Carbon;
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
        $sequence = Sequence::fromArray($events, "SID1");
        
        // call a service
        $request = new ApplyPatternRequest($pattern, $sequence);
        $service = new ApplyPattern($request);
        $report  = $service->execute()->getReport();
        
        // make sure we get to the final point
        $this->assertTrue($report->isMatched());
        $this->assertEquals($sequence->getId(), $report->getSequenceId());
        
    }
    
    function test_service_detects_sequence_which_does_not_match_the_pattern()
    {
        
        // Set A
        list($pattern_config, $events) = PatternDataProvider::getSetA_no_match();
        
        $config   = new Config($pattern_config);
        $pattern  = new Pattern($config);
        $sequence = Sequence::fromArray($events, "SID1");
        
        // call a service
        $request = new ApplyPatternRequest($pattern, $sequence);
        $service = new ApplyPattern($request);
        $report  = $service->execute()->getReport();
        
        // make sure we get to the final point
        $this->assertFalse($report->isMatched());
        
    }
    
    function test_service_matches_sequence_with_loops()
    {
        
        // Set A
        list($pattern_config, $events) = PatternDataProvider::getSetLoop();
        
        $config   = new Config($pattern_config);
        $pattern  = new Pattern($config);
        $sequence = Sequence::fromArray($events, "SID1");
        
        // call a service
        $request = new ApplyPatternRequest($pattern, $sequence);
        $service = new ApplyPattern($request);
        $report  = $service->execute()->getReport();
        
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
        
        $time   = Carbon::now();
        $events = [
            ["name" => "A", "occurred_at" => $time->addHour()],
            ["name" => "B", "occurred_at" => $time->addHour()],
            ["name" => "login", "occurred_at" => $time->addHour()],
            ["name" => "C", "occurred_at" => $time->addHour()],
            ["name" => "search", "occurred_at" => $time->addHour()],
            ["name" => "search", "occurred_at" => $time->addHour()],
            ["name" => "login", "occurred_at" => $time->addHour()],
            ["name" => "D", "occurred_at" => $time->addHour()],
            ["name" => "D", "occurred_at" => $time->addHour()],
            ["name" => "checkout", "occurred_at" => $time->addHour()],
            ["name" => "E", "occurred_at" => $time->addHour()],
            ["name" => "C", "occurred_at" => $time->addHour()],
        ];
        
        $config   = new Config($pattern_config);
        $pattern  = new Pattern($config);
        $sequence = Sequence::fromArray($events, "SID1");
        
        // call a service
        $request = new ApplyPatternRequest($pattern, $sequence);
        $service = new ApplyPattern($request);
        $report  = $service->execute()->getReport();
        
        // make sure we get to the final point
        $this->assertTrue($report->isMatched());
        
        // Make sure Report tells me what pattern's vertexes was found in sequence
        $this->assertEquals(3, count($report->getMatchedEvents()));
        
        
    }
    
    function test_service_apply_pattern_with_duplicate_events_in_pattern()
    {
        
        $pattern_config = [
            [
                "id"   => 1,
                "name" => "search",
                "ways" => [
                    [
                        "then" => 2,
                    ],
                    [
                        "then" => 4,
                    ],
                ],
            ],
            [
                "id"   => 2,
                "name" => "search_results",
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
                "name" => "search",
                "ways" => [
                    [
                        "then" => 2,
                    ],
                    [
                        "then" => 4,
                    ],
                ],
            ],
            [
                "id"   => 4,
                "name" => "checkout",
            ],
        ];
        
        $time   = Carbon::now();
        $events = [
            ["name" => "login", "occurred_at" => $time->addHour()],
            ["name" => "C", "occurred_at" => $time->addHour()],
            ["name" => "search", "occurred_at" => $time->addHour()],
            ["name" => "search_results", "occurred_at" => $time->addHour()],
            ["name" => "search", "occurred_at" => $time->addHour()],
            ["name" => "D", "occurred_at" => $time->addHour()],
            ["name" => "checkout", "occurred_at" => $time->addHour()],
            ["name" => "C", "occurred_at" => $time->addHour()],
        ];
        
        $config   = new Config($pattern_config);
        $pattern  = new Pattern($config);
        $sequence = Sequence::fromArray($events, "SID1");
        
        // call a service
        $request = new ApplyPatternRequest($pattern, $sequence);
        $service = new ApplyPattern($request);
        $report  = $service->execute()->getReport();
        
        // make sure we get to the final point
        $this->assertTrue($report->isMatched());
        
        // Make sure Report tells me what pattern's vertexes was found in sequence
        $this->assertEquals(4, count($report->getMatchedEvents()));
        
        
    }
    
}