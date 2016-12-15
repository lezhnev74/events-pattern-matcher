<?php

namespace Lezhnev74\EventsPatternMatcher\Tests\Pattern;


class PatternDataProvider
{
    static function getSetA()
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
                ],
            ],
            [
                "id"   => 3,
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
    
    static function getSetA_no_match()
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
                ],
            ],
            [
                "id"   => 3,
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
            ["name" => "E"],
            ["name" => "C"],
        ];
        
        return [$pattern_config, $events];
    }
    
    static function getSetB()
    {
        $pattern_config = [
            [
                "id"   => 1,
                "name" => "login",
                "ways" => [
                    [
                        "then" => 2,
                    ],
                    [
                        "then" => 3,
                    ],
                ],
            ],
            [
                "id"   => 2,
                "name" => "search",
                "ways" => [
                    [
                        "then" => 4,
                    ],
                    [
                        "then" => 3,
                    ],
                ],
            ],
            [
                "id"   => 3,
                "name" => "product_details",
                "ways" => [
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
    
    static function getSetLoop()
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
            // search and results are in circle
            [
                "id"   => 2,
                "name" => "search",
                "ways" => [
                    [
                        "then" => 4,
                    ],
                    [
                        "then" => 3,
                    ],
                ],
            ],
            [
                "id"   => 3,
                "name" => "results",
                "ways" => [
                    [
                        "then" => 2,
                    ],
                ],
            ],
            [
                "id"   => 4,
                "name" => "checkout",
            ],
        ];
        
        $events = [
            ["name" => "A"],
            ["name" => "B"],
            ["name" => "login"],
            ["name" => "C"],
            ["name" => "search"],
            ["name" => "results"],
            ["name" => "search"],
            ["name" => "results"],
            ["name" => "search"],
            ["name" => "checkout"],
            ["name" => "E"],
            ["name" => "C"],
        ];
        
        return [$pattern_config, $events];
    }
}