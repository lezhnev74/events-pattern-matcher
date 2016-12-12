<?php

namespace Lezhnev74\EventsPatternMatcher\Tests;


use Lezhnev74\EventsPatternMatcher\Data\Pattern\Config\BadConfig;
use Lezhnev74\EventsPatternMatcher\Data\Pattern\Config\Config;

class PatternConfigTest extends \PHPUnit_Framework_TestCase
{
    function test_it_accepts_good_config()
    {
        list($pattern_config, $events) = DataProvider::getSetA();
        
        new Config($pattern_config);
    }
    
    function badConfigProvider() {
        return [
            // event which leads to nowhere
            [
                [
                    [
                        "name" => "login",
                        "ways" => [
                            [
                                "then" => "not_existing",
                            ],
                        ],
                    ],
                ]
            ],
            // event which is detached and not connnected to any other vertex
            [
                [
                    [
                        "name" => "login",
                        "ways" => [
                            [
                                "then" => "search",
                            ],
                        ],
                    ],
                    [
                        "name" => "search",
                    ],
                    [
                        "name" => "detached_state",
                    ],
                ]
            ],
        ];
    }
    
    /**
     * @param $pattern_config
     * @dataProvider badConfigProvider
     */
    function test_it_rejects_bad_config($pattern_config)
    {
        $this->expectException(BadConfig::class);
        
        $config = new Config($pattern_config);
    }
}