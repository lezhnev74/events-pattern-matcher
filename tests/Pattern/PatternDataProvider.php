<?php

namespace Lezhnev74\EventsPatternMatcher\Tests\Pattern;


class PatternDataProvider
{
    static function getSetA()
    {
        $pattern_config = [
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
                "ways" => [
                    [
                        "then" => "checkout",
                    ],
                ],
            ],
            [
                "name" => "checkout",
            ],
        ];
        
        $events = [
            ["name" => "A"],
            ["name" => "B"],
            ["name" => "login"],
            ["name" => "C"],
            ["name" => "search"],
            ["name" => "D"],
            ["name" => "D"],
            ["name" => "checkout"],
            ["name" => "E"],
            ["name" => "C"],
        ];
        
        return [$pattern_config, $events];
    }
}