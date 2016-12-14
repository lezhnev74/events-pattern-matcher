<?php
namespace Lezhnev74\EventsPatternMatcher\Data\Report;

/**
 * Class Report
 * Contains information about results of matching a sequence of events against a pattern
 */
class Report
{
    private $result;
    
    public function __construct(bool $result)
    {
        $this->result = $result;
    }
    
    /**
     * @return boolean
     */
    public function isMatched(): bool
    {
        return $this->result;
    }
    
    
}