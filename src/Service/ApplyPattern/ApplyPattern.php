<?php
namespace Lezhnev74\EventsPatternMatcher\Service\ApplyPattern;


use Lezhnev74\EventsPatternMatcher\Data\Pattern\Pattern;
use Lezhnev74\EventsPatternMatcher\Data\Report\Report;
use Lezhnev74\EventsPatternMatcher\Service\Request;
use Lezhnev74\EventsPatternMatcher\Service\Response;
use Lezhnev74\EventsPatternMatcher\Service\Service;
use LightFsm\StateMachine;

/**
 * Class ApplyPattern
 *
 * This class will validate a given sequence of events against a given pattern.
 * It will return a report containing results of such validation.
 */
class ApplyPattern implements Service
{
    private $request;
    private $state_machine;
    
    /**
     * ApplyPattern constructor.
     *
     * @param $request
     */
    public function __construct(ApplyPatternRequest $request)
    {
        $this->request       = $request;
        $this->state_machine = $this->makeMachine($request->getPattern());
    }
    
    
    function execute(): Report
    {
        $matched       = false;
        $pattern       = $this->request->getPattern();
        $sequence      = $this->request->getSequence();
        $state_machine = $this->state_machine;
        
        //
        // Now execute the event sequence one by one
        //
        foreach ($sequence->getEvents() as $event) {
            $state_machine->fire($event->getName());
            
            
            //
            // Detect if we reached the final vertex
            //
            $current_state          = $state_machine->getCurrentState();
            $current_pattern_vertex = $pattern->getVertexById($current_state);
            if ($pattern->isFinalVertex($current_pattern_vertex)) {
                $matched = true;
                break;
            }
        }
        
        $report = new Report($matched);
        
        return $report;
    }
    
    /**
     * Prepare a state machine based on given Pattern graph
     * All states are marked as Graph's vertex IDs, transitions are marked as event names
     *
     * @param Pattern $pattern
     *
     * @return StateMachine
     */
    private function makeMachine(Pattern $pattern): StateMachine
    {
        $initial_state = $pattern->getEntryVertex();
        $sm            = new StateMachine($initial_state->getId());
        
        foreach ($pattern->getVertices() as $vertex) {
            $state = $sm->configure($vertex->getId());
            foreach ($vertex->getEdgesOut() as $edge) {
                $target_vertex     = $edge->getVertexEnd();
                $target_event_name = $pattern->getEventNameOfVertex($target_vertex);
                //
                // Allow transfer to the next state
                //
                $state->permit($target_event_name, $target_vertex->getId());
            }
        }
        
        return $sm;
    }
}