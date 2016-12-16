<?php
namespace Lezhnev74\EventsPatternMatcher\Service\ApplyToSequences;

use Lezhnev74\EventsPatternMatcher\Data\Pattern\Pattern;
use Lezhnev74\EventsPatternMatcher\Data\Sequence\Sequence;
use Lezhnev74\EventsPatternMatcher\Data\Sequence\SequenceGroup;

class ApplyToSequencesRequest
{
    /** @var  SequenceGroup */
    private $sequences;
    /** @var  Pattern */
    private $pattern;
    
    /**
     * ApplyToSequencesRequest constructor.
     *
     * @param SequenceGroup $sequences
     * @param Pattern       $pattern
     */
    public function __construct(SequenceGroup $sequences, Pattern $pattern)
    {
        $this->sequences = $sequences;
        $this->pattern   = $pattern;
    }
    
    /**
     * @return string
     */
    public function getSequencesName(): string
    {
        return $this->sequences->getTitle();
    }
    
    /**
     * @return array
     */
    public function getSequences(): array
    {
        return $this->sequences->getSequences();
    }
    
    /**
     * @return Pattern
     */
    public function getPattern(): Pattern
    {
        return $this->pattern;
    }
    
    
}