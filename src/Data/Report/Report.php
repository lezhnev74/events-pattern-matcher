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
        $this->result          = $result;
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
    
}