<?php

namespace Lezhnev74\EventsPatternMatcher\Service\ApplyToSequences;


use Lezhnev74\EventsPatternMatcher\Data\Report\SummaryReport;

class ApplyToSequencesResponse
{
    /** @var  SummaryReport */
    private $summary_report;
    
    /**
     * ApplyToSequencesResponse constructor.
     *
     * @param SummaryReport $summary_report
     */
    public function __construct(SummaryReport $summary_report) { $this->summary_report = $summary_report; }
    
    /**
     * @return SummaryReport
     */
    public function getSummaryReport(): SummaryReport
    {
        return $this->summary_report;
    }
    
    
}