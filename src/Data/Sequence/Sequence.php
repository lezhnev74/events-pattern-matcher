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
    /** @var  string ID for given sequence */
    private $id;
    
    /**
     * Sequence constructor.
     *
     * @param $events
     */
    public function __construct(iterable $events, string $id)
    {
        $this->events = $events;
        $this->id     = $id;
    }
    
    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
    
    /**
     * Make events from given array of JSON data
     *
     * @param array  $events
     * @param string $id of the sequence (user_id, or session_id or whatever)
     */
    static function fromArray(array $events, string $id)
    {
        $event_objects = [];
        
        foreach ($events as $event) {
            $event_objects[] = Event::fromArray($event);
        }
        
        return new self($event_objects, $id);
    }
    
    function getEvents(): iterable
    {
        return $this->events;
    }
}