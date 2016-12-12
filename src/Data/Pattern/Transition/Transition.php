<?php

namespace Lezhnev74\EventsPatternMatcher\Data\Pattern\Transition;


use Lezhnev74\EventsPatternMatcher\Data\Pattern\State\State;

class Transition
{
    private $by_event;
    private $target_state;
    
    public function __construct(string $by_event, string $target_state)
    {
        $this->by_event     = $by_event;
        $this->target_state = $target_state;
    }
    
    public function getByEvent(): string
    {
        return $this->by_event;
    }
    
    public function getTargetState(): string
    {
        return $this->target_state;
    }
    
    
}