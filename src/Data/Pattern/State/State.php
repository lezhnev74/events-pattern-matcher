<?php

namespace Lezhnev74\EventsPatternMatcher\Data\Pattern\State;


use Lezhnev74\EventsPatternMatcher\Data\Pattern\Transition\Transition;

class State
{
    private $name;
    private $transitions;
    
    public function __construct(string $name, array $transitions = [])
    {
        $this->name        = $name;
        $this->transitions = $transitions;
        
        $this->validate();
    }
    
    protected function validate()
    {
        //
        // Validate transitions types
        //
        foreach ($this->transitions as $transition) {
            if (!is_a($transition, Transition::class)) {
                throw new BadState("One of the transitions has unexpected type: " . get_class($transition));
            }
        }
    }
    
    public function getName(): string
    {
        return $this->name;
    }
    
    
}