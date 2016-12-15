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
    
    private $matched_events = [];
    
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
        $pattern = $this->request->getPattern();
        
        $initial_state = $pattern->getEntryVertex();
        $sm            = new StateMachine($initial_state->getId(), function ($state) use ($pattern) {
            //
        }, true);
        
        //
        // Init states for machine
        //
        foreach ($pattern->getVertices() as $vertex) {
            $state = $sm->configure($vertex->getId());
            
            $state->onEntry(function ($is_substate, $data, $current_state) use ($pattern) {
                //
                // This callback is executed on each state entry. Since states are from Pattern graph
                // I want to track each state entry to know what pattern's vertexes were matched against sequence of events
                //
                
                //
                // Init storage for matched events
                //
                if (!isset($this->matched_events[$current_state])) {
                    $this->matched_events[$current_state] = [];
                }
                //
                // find pattern vertex by id
                //
                $pattern_vertex = $pattern->getVertexById($current_state);
                $event_name     = $pattern->getEventNameOfVertex($pattern_vertex);
                //
                // Put event name to storage
                //
                $this->matched_events[$current_state][] = [
                    'event_name' => $event_name,
                ];
                
            });
            
            
            foreach ($vertex->getEdgesOut()->getEdgesDistinct() as $edge) {
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
    
    function execute(): ApplyPatternResponse
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
        
        $report = new Report($sequence->getId(), $matched, $this->matched_events);
        
        return new ApplyPatternResponse($report);
    }
    
}