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
use Lezhnev74\EventsPatternMatcher\Service\ApplyToSequences\ApplyToSequences;
use Lezhnev74\EventsPatternMatcher\Service\ApplyToSequences\ApplyToSequencesRequest;
use Lezhnev74\EventsPatternMatcher\Tests\Pattern\PatternDataProvider;

class ApplyToSequencesTest extends \PHPUnit_Framework_TestCase
{
    function test_service_returns_correct_report()
    {
        
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
                    ["then" => 4],
                ],
            ],
            [
                "id"   => 3,
                "name" => "blog",
                "ways" => [
                    ["then" => 4],
                ],
            ],
            [
                "id"   => 4,
                "name" => "checkout",
            ],
        ];
        
        $time      = Carbon::now();
        $sequences = [
            [
                ["name" => "login", "occurred_at" => $time->addHour()],
                ["name" => "search", "occurred_at" => $time->addHour()],
                ["name" => "checkout", "occurred_at" => $time->addHour()],
            ],
            [
                ["name" => "login", "occurred_at" => $time->addHour()],
                ["name" => "checkout", "occurred_at" => $time->addHour()],
            ],
            [
                ["name" => "login", "occurred_at" => $time->addHour()],
                ["name" => "C", "occurred_at" => $time->addHour()],
                ["name" => "search", "occurred_at" => $time->addHour()],
                ["name" => "search_results", "occurred_at" => $time->addHour()],
                ["name" => "search", "occurred_at" => $time->addHour()],
                ["name" => "D", "occurred_at" => $time->addHour()],
                ["name" => "checkout", "occurred_at" => $time->addHour()],
                ["name" => "C", "occurred_at" => $time->addHour()],
            ],
            [
                ["name" => "C", "occurred_at" => $time->addHour()],
                ["name" => "login", "occurred_at" => $time->addHour()],
                ["name" => "C", "occurred_at" => $time->addHour()],
                ["name" => "blog", "occurred_at" => $time->addHour()],
                ["name" => "search", "occurred_at" => $time->addHour()],
                ["name" => "checkout", "occurred_at" => $time->addHour()],
                ["name" => "C", "occurred_at" => $time->addHour()],
            ],
        ];
        
        
        $config  = new Config($pattern_config);
        $pattern = new Pattern($config);
        
        
        // call a service
        $request  = new ApplyToSequencesRequest("SE1", array_map(function ($events) {
            return Sequence::fromArray($events, "random_" . rand());
        }, $sequences), $pattern);
        $service  = new ApplyToSequences($request);
        $response = $service->execute();
        
        //
        // Assert summary report
        //
        $this->assertEquals(4, $response->getSummaryReport()->totalReports());
        $this->assertEquals(3, $response->getSummaryReport()->matchedReportsCount());
        $this->assertEquals(3, $response->getSummaryReport()->patternVertexMatchedCount(1));
        $this->assertEquals(3, $response->getSummaryReport()->patternVertexMatchedCount(2));
        $this->assertEquals(2, $response->getSummaryReport()->patternVertexMatchedTransitionsFromCount(4, 2));
        $this->assertEquals(1, $response->getSummaryReport()->patternVertexMatchedTransitionsFromCount(4, 3));
        
        
    }
    
}