<?php

namespace Lezhnev74\EventsPatternMatcher\Data\Pattern;


use Lezhnev74\EventsPatternMatcher\Data\Pattern\Config\Config;
use Lezhnev74\EventsPatternMatcher\Data\Sequence\Sequence;
use LightFsm\StateMachine;

class Pattern
{
    private $state_machine;
    
    /**
     * Pattern constructor.
     * Here I want to setup the state machine based on given config array
     *
     * @param array $config
     */
    public function __construct(Config $config)
    {
        $this->initStateMachine($config);
    }
    
    
    /**
     * Validate if this pattern matches given sequence of events
     *
     * @param Sequence $sequence
     *
     * @return bool
     */
    function validateSequence(Sequence $sequence): bool
    {
        return true;
    }
    
    
    private function initStateMachine(Config $config)
    {
        $this->state_machine = new StateMachine("__init_state");
        $this->state_machine->configure("__init_state")->permit("login", "login");
    }
}