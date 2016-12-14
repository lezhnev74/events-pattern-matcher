<?php

namespace Lezhnev74\EventsPatternMatcher\Tests\Service;

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
    
    
}