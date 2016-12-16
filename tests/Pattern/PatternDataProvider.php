<?php

namespace Lezhnev74\EventsPatternMatcher\Tests\Pattern;


use Carbon\Carbon;

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
        
        $now    = Carbon::now();
        $events = [
            ["name" => "A", "occurred_at" => $now],
            ["name" => "B", "occurred_at" => $now->addHour()],
            ["name" => "login", "occurred_at" => $now->addHour()],
            ["name" => "C", "occurred_at" => $now->addHour()],
            ["name" => "search", "occurred_at" => $now->addHour()],
            ["name" => "D", "occurred_at" => $now->addHour()],
            ["name" => "D", "occurred_at" => $now->addHour()],
            ["name" => "checkout", "occurred_at" => $now->addHour()],
            ["name" => "E", "occurred_at" => $now->addHour()],
            ["name" => "C", "occurred_at" => $now->addHour()],
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
        
        $now    = Carbon::now();
        $events = [
            ["name" => "A", "occurred_at" => $now->addHour()],
            ["name" => "B", "occurred_at" => $now->addHour()],
            ["name" => "login", "occurred_at" => $now->addHour()],
            ["name" => "C", "occurred_at" => $now->addHour()],
            ["name" => "search", "occurred_at" => $now->addHour()],
            ["name" => "D", "occurred_at" => $now->addHour()],
            ["name" => "D", "occurred_at" => $now->addHour()],
            ["name" => "E", "occurred_at" => $now->addHour()],
            ["name" => "C", "occurred_at" => $now->addHour()],
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
        
        $time   = Carbon::now();
        $events = [
            ["name" => "A", "occurred_at" => $time->addHour()],
            ["name" => "B", "occurred_at" => $time->addHour()],
            ["name" => "login", "occurred_at" => $time->addHour()],
            ["name" => "C", "occurred_at" => $time->addHour()],
            ["name" => "search", "occurred_at" => $time->addHour()],
            ["name" => "D", "occurred_at" => $time->addHour()],
            ["name" => "D", "occurred_at" => $time->addHour()],
            ["name" => "checkout", "occurred_at" => $time->addHour()],
            ["name" => "E", "occurred_at" => $time->addHour()],
            ["name" => "C", "occurred_at" => $time->addHour()],
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
        
        $time   = Carbon::now();
        $events = [
            ["name" => "A", "occurred_at" => $time->addHour()],
            ["name" => "B", "occurred_at" => $time->addHour()],
            ["name" => "login", "occurred_at" => $time->addHour()],
            ["name" => "C", "occurred_at" => $time->addHour()],
            ["name" => "search", "occurred_at" => $time->addHour()],
            ["name" => "results", "occurred_at" => $time->addHour()],
            ["name" => "search", "occurred_at" => $time->addHour()],
            ["name" => "results", "occurred_at" => $time->addHour()],
            ["name" => "search", "occurred_at" => $time->addHour()],
            ["name" => "checkout", "occurred_at" => $time->addHour()],
            ["name" => "E", "occurred_at" => $time->addHour()],
            ["name" => "C", "occurred_at" => $time->addHour()],
        ];
        
        return [$pattern_config, $events];
    }
}