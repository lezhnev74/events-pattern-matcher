<?php

namespace Lezhnev74\EventsPatternMatcher\Service\ApplyToSequences;


use Lezhnev74\EventsPatternMatcher\Data\Report\SummaryReport;
use Lezhnev74\EventsPatternMatcher\Service\ApplyPattern\ApplyPattern;
use Lezhnev74\EventsPatternMatcher\Service\ApplyPattern\ApplyPatternRequest;
use Lezhnev74\EventsPatternMatcher\Service\Service;

class ApplyToSequences implements Service
{
    /** @var  ApplyToSequencesRequest */
    private $request;
    
    /**
     * ApplyToSequences constructor.
     *
     * @param ApplyToSequencesRequest $request
     */
    public function __construct(ApplyToSequencesRequest $request) { $this->request = $request; }
    
    function execute(): ApplyToSequencesResponse
    {
        $reports = [];
        
        foreach ($this->request->getSequences() as $sequence) {
            //
            // Call matching service
            //
            $request   = new ApplyPatternRequest($this->request->getPattern(), $sequence);
            $service   = new ApplyPattern($request);
            $response  = $service->execute();
            $reports[] = $response->getReport();
        }
        
        //
        // Ok, all matching is done and I can return reponse
        //
        $summary_report = new SummaryReport($this->request->getSequencesName(), $reports);
        
        return new ApplyToSequencesResponse($summary_report);
    }
    
    
}