<?php

namespace Lezhnev74\EventsPatternMatcher\Data\Sequence;


/*
 * Contains sequence of events to validate our assumption on
 */
use Lezhnev74\EventsPatternMatcher\Data\Event\Event;

class Sequence
{
    /**
     * @var iterable
     */
    private $events;
    
    /**
     * Sequence constructor.
     *
     * @param $events
     */
    public function __construct(iterable $events)
    {
        $this->events = $events;
    }
    
    /**
     * Make events from given array of JSON data
     *
     * @param array $events
     */
    static function fromArray(array $events)
    {
        $event_objects = [];
        
        foreach ($events as $event) {
            $event_objects[] = Event::fromArray($event);
        }
        
        return new self($event_objects);
    }
    
    function getEvents(): iterable
    {
        return $this->events;
    }
}