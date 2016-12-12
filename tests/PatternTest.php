<?php
namespace Lezhnev74\EventsPatternMatcher\Tests;

use Lezhnev74\EventsPatternMatcher\Data\Pattern\Config\Config;
use Lezhnev74\EventsPatternMatcher\Data\Pattern\Pattern;

class PatternTest extends \PHPUnit_Framework_TestCase
{
    function test_pattern_is_checked_against_the_es_with_success()
    {
        list($pattern_config, $events) = DataProvider::getSetA();
        
        $config  = new Config($pattern_config);
        $pattern = new Pattern($config);
        $result  = $pattern->applyOn($events);
        
        $this->assertTrue($result);
    }
}