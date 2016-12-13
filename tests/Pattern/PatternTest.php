<?php

namespace Lezhnev74\EventsPatternMatcher\Tests\Pattern;

use Lezhnev74\EventsPatternMatcher\Data\Pattern\BadPattern;
use Lezhnev74\EventsPatternMatcher\Data\Pattern\Config\Config;
use Lezhnev74\EventsPatternMatcher\Data\Pattern\Pattern;

class PatternTest extends \PHPUnit_Framework_TestCase
{
    function test_it_accepts_good_config()
    {
        list($pattern_config, $events) = PatternDataProvider::getSetA();
        
        $config = new Config($pattern_config);
        new Pattern($config);
    }
    
    function test_it_will_protect_from_many_components_in_graph()
    {
        $this->expectException(BadPattern::class);
        $pattern_config = [
            [
                "name" => "login",
            ],
            [
                "name" => "checkout",
            ],
        ];
        
        $config = new Config($pattern_config);
        new Pattern($config);
    }
    
}