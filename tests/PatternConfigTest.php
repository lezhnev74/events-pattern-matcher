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
        
        new Config($pattern_config);
    }
}