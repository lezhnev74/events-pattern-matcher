<?php
namespace Lezhnev74\EventsPatternMatcher\Tests\Pattern;

use Carbon\Carbon;
use Lezhnev74\EventsPatternMatcher\Data\Sequence\Sequence;
use Lezhnev74\EventsPatternMatcher\Data\Sequence\SequenceGroup;

class SequenceGroupTest extends \PHPUnit_Framework_TestCase
{
    function test_group_can_split_itself_to_subgroups()
    {
        Carbon::setTestNow(Carbon::parse("22.01.2015 01:00:00"));
        $time      = Carbon::now();
        $sequences = [
            [
                ["name" => "login", "occurred_at" => $time->addHours(1)->timestamp],
                ["name" => "search", "occurred_at" => $time->addHours(2)->timestamp],
                ["name" => "checkout", "occurred_at" => $time->addHours(3)->timestamp],
            ],
            [
                ["name" => "login", "occurred_at" => $time->addHours(4)->timestamp],
                ["name" => "checkout", "occurred_at" => $time->addHours(7)->timestamp],
            ],
            [
                ["name" => "login", "occurred_at" => $time->addHours(35)->timestamp],
                ["name" => "C", "occurred_at" => $time->addHours(36)->timestamp],
                ["name" => "search", "occurred_at" => $time->addHours(37)->timestamp],
                ["name" => "search_results", "occurred_at" => $time->addHours(38)->timestamp],
                ["name" => "search", "occurred_at" => $time->addHours(39)->timestamp],
                ["name" => "D", "occurred_at" => $time->addHours(39)->timestamp],
                ["name" => "checkout", "occurred_at" => $time->addHours(39)->timestamp],
                ["name" => "C", "occurred_at" => $time->addHours(40)->timestamp],
            ],
            [
                ["name" => "C", "occurred_at" => $time->addHours(150)->timestamp],
                ["name" => "login", "occurred_at" => $time->addHours(151)->timestamp],
                ["name" => "C", "occurred_at" => $time->addHours(152)->timestamp],
                ["name" => "blog", "occurred_at" => $time->addHours(152)->timestamp],
                ["name" => "search", "occurred_at" => $time->addHours(153)->timestamp],
                ["name" => "checkout", "occurred_at" => $time->addHours(154)->timestamp],
                ["name" => "C", "occurred_at" => $time->addHours(155)->timestamp],
            ],
        ];
        
        $group = new SequenceGroup(array_map(function ($events) {
            return Sequence::fromArray($events, "random_" . rand());
        }, $sequences), "Initial group");
        
        $sub_groups = $group->splitInGroups(function (Sequence $sequence) {
            // I want to group by first occurence of Login event
            foreach ($sequence->getEvents() as $event) {
                if ($event->getName() == "login") {
                    return $event->getOccurredAt()->format('d M Y');
                }
            }
            
            return "Unknown";
        });
        
        $this->assertEquals(3, count($sub_groups));
        
    }
}