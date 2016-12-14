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
        //
        // Now execute the event sequence one by one
        //
        foreach ($this->request->getSequence()->getEvents() as $event) {
            $this->state_machine->fire($event->getName());
        }
        
        $result = true;
        
        $report = new Report($result);
        
        return $report;
    }
    
    /**
     * Prepare a state machine based on given Pattern graph
     *
     * @param Pattern $pattern
     *
     * @return StateMachine
     */
    private function makeMachine(Pattern $pattern): StateMachine
    {
        $initial_event_name = $pattern->getEventNameOfVertex($pattern->getEntryVertex());
        $sm                 = new StateMachine($initial_event_name);
        
        foreach ($pattern->getVertices() as $vertex) {
            $event_name = $pattern->getEventNameOfVertex($vertex);
            $state      = $sm->configure($event_name);
            foreach ($vertex->getEdgesOut() as $edge) {
                $target_vertex    = $edge->getVertexEnd();
                $tager_event_name = $pattern->getEventNameOfVertex($target_vertex);
                //
                // Allow transfer to the next state
                //
                $state->permit($tager_event_name, $tager_event_name);
            }
        }
        
        return $sm;
    }
}