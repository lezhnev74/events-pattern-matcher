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
    
    public function __construct(bool $result, array $matchedEvents = [])
    {
        $this->result        = $result;
        $this->matchedEvents = $matchedEvents;
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
    
}