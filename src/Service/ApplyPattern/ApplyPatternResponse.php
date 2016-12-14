<?php

namespace Lezhnev74\EventsPatternMatcher\Service\ApplyPattern;


use Lezhnev74\EventsPatternMatcher\Data\Report\Report;

class ApplyPatternResponse
{
    private $report;
    
    public function __construct(Report $report)
    {
        $this->report = $report;
    }
    
    public function getReport(): Report
    {
        return $this->report;
    }
    
    
}