<?php

namespace Lezhnev74\EventsPatternMatcher\Data\Sequence;


/*
 * Contains sequence of events to validate our assumption on
 */
class Sequence
{
    private $events;
    
    /**
     * Sequence constructor.
     *
     * @param $events
     */
    public function __construct(array $events = [])
    {
        $this->events = $events;
    }
    
    /**
     * @return array
     */
    public function getEvents()
    {
        return $this->events;
    }
    
    
}