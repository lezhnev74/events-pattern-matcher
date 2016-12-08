<?php

namespace Lezhnev74\EventsPatternMatcher\Tests;


use Lezhnev74\EventsPatternMatcher\Data\Pattern\Config\Config;

class PatternConfigTest extends \PHPUnit_Framework_TestCase
{
    function test_it_accepts_good_config()
    {
        $config = [
            "event_states" => [
                [
                    "name"        => "login",
                    "transitions" => [
                        [
                            "to" => "search",
                        ],
                        [
                            "to" => "checkout",
                        ],
                    ],
                ],
                [
                    "name"        => "search",
                    "transitions" => [
                        [
                            "to" => "checkout",
                        ],
                    ],
                ],
                [
                    "name" => "checkout",
                ],
            ],
        ];
        
        new Config($config);
    }
}