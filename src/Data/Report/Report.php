<?php
namespace Lezhnev74\EventsPatternMatcher\Data\Report;

/**
 * Class Report
 * Contains information about results of matching a sequence of events against a pattern
 */
class Report
{
    private $result;
    private $matchedEvents = [];
    private $sequence_id;
    
    public function __construct(string $sequence_id, bool $result, array $matchedEvents = [])
    {
        $this->result        = $result;
        $this->matchedEvents = $matchedEvents;
        $this->sequence_id   = $sequence_id;
        
        $this->validateMatchedEvents();
    }
    
    /**
     * @return string
     */
    public function getSequenceId()
    {
        return $this->sequence_id;
    }
    
    
    /**
     * @return boolean
     */
    public function isMatched(): bool
    {
        return $this->result;
    }
    
    
    /** Get all events that was found in the sequence of events */
    function getMatchedEvents(): array
    {
        return $this->matchedEvents;
    }
    
    /**
     *  Get all mathced events for given pattern's vertex ID
     */
    function getMatchedEventsForVertex(string $vertex_id): array
    {
        if (isset($this->getMatchedEvents()[$vertex_id])) {
            return $this->getMatchedEvents()[$vertex_id];
        }
        
        return [];
    }
    
    
    /**
     * Make sure events has desired format
     */
    private function validateMatchedEvents()
    {
        $pattern = [
            ":integer *" => [
                "*" => [
                    "event_name" => ":string min(1)",
                ],
            ],
        ];
        
        \matchmaker\catches($this->matchedEvents, $pattern);
    }
}