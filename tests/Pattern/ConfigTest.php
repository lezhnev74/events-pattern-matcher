<?php

namespace Lezhnev74\EventsPatternMatcher\Tests\Pattern;


use Lezhnev74\EventsPatternMatcher\Data\Pattern\Config\BadConfig;
use Lezhnev74\EventsPatternMatcher\Data\Pattern\Config\Config;

class ConfigTest extends \PHPUnit_Framework_TestCase
{
    function test_it_accepts_good_config()
    {
        list($pattern_config, $events) = PatternDataProvider::getSetA();
        
        new Config($pattern_config);
    }
    
    function badConfigProvider()
    {
        return [
            // event which leads to nowhere
            [
                [
                    [
                        "id"   => "L1",
                        "name" => "login",
                        "ways" => [
                            [
                                "then" => "not_existing",
                            ],
                        ],
                    ],
                ],
            ],
            // event which does not have a good name field
            [
                [
                    [
                        "id"   => "L1",
                        "nameWRONG" => "login",
                        "ways"      => [
                            [
                                "then" => "S1",
                            ],
                        ],
                    ],
                    [
                        "id"   => "S1",
                        "name" => "search",
                    ],
                ],
            ],
            // event which does not have a good ways field
            [
                [
                    [
                        "id"   => "L1",
                        "name" => "login",
                        "ways" => [
                            [
                                "thenWrong" => "search",
                            ],
                        ],
                    ],
                    [
                        "id"   => "S1",
                        "name" => "search",
                    ],
                ],
            ],
        ];
    }
    
    /**
     * @param $pattern_config
     *
     * @dataProvider badConfigProvider
     */
    function test_it_rejects_bad_config($pattern_config)
    {
        $this->expectException(BadConfig::class);
        
        $config = new Config($pattern_config);
    }
}