<?php
namespace Lezhnev74\EventsPatternMatcher\Data\Report;

/**
 * Contains multiple reports. Allows to calculate aggregated values
 *
 */
class SummaryReport
{
    private $id;
    private $reports         = [];
    private $matched_reports = [];
    
    /**
     * SummaryReport constructor.
     *
     * @param       $id
     * @param array $reports
     */
    public function __construct($id, array $reports)
    {
        $this->id              = $id;
        $this->reports         = $reports;
        $this->matched_reports = array_filter($this->reports, function (Report $report) {
            return $report->isMatched();
        });
        
        //
        // Make sure we have only reports
        //
        foreach ($reports as $report) {
            if (!is_a($report, Report::class)) {
                throw new BadReport("Report object expected, but given: " . get_class($report));
            }
        }
    }
    
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @return array
     */
    public function getReports(): array
    {
        return $this->reports;
    }
    
    /**
     * How many reports we have
     *
     * @return int
     */
    public function totalReports(): int
    {
        return count($this->reports);
    }
    
    public function matchedReportsCount()
    {
        return count($this->matched_reports);
    }
    
    /**
     * Get total number of times event was occurred on the pattern graph
     *
     * @param int $vertex_id
     *
     * @return int
     */
    public function patternVertexMatchedCount(int $vertex_id): int
    {
        $count = 0;
        
        foreach ($this->matched_reports as $report) {
            $count += count($report->getMatchedEventsForVertex($vertex_id));
        }
        
        return $count;
    }
    
    /**
     * @param int $vertex_id
     * @param int $from_vertex_id
     *
     * @return int how many transitions were detected from given vertex
     */
    public function patternVertexMatchedTransitionsFromCount(int $vertex_id, int $from_vertex_id): int
    {
        $count = 0;
        
        foreach ($this->matched_reports as $report) {
            $count += count($report->getMatchedEventsForVertexTransitionedFromVertex($vertex_id, $from_vertex_id));
        }
        
        return $count;
    }
}