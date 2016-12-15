<?php
namespace Lezhnev74\EventsPatternMatcher\Service\ApplyToSequences;

use Lezhnev74\EventsPatternMatcher\Data\Pattern\Pattern;
use Lezhnev74\EventsPatternMatcher\Data\Sequence\Sequence;

class ApplyToSequencesRequest
{
    /** @var  string */
    private $sequences_name;
    /** @var  array */
    private $sequences;
    /** @var  Pattern */
    private $pattern;
    
    /**
     * ApplyToSequencesRequest constructor.
     *
     * @param string  $sequences_name
     * @param array   $sequences
     * @param Pattern $pattern
     */
    public function __construct($sequences_name, array $sequences, Pattern $pattern)
    {
        $this->sequences_name = $sequences_name;
        $this->sequences      = $sequences;
        $this->pattern        = $pattern;
        
        foreach ($sequences as $sequence) {
            if (!is_a($sequence, Sequence::class)) {
                throw new \InvalidArgumentException("Expected sequence object but detect one is: " . get_class($sequence));
            }
        }
    }
    
    /**
     * @return string
     */
    public function getSequencesName(): string
    {
        return $this->sequences_name;
    }
    
    /**
     * @return array
     */
    public function getSequences(): array
    {
        return $this->sequences;
    }
    
    /**
     * @return Pattern
     */
    public function getPattern(): Pattern
    {
        return $this->pattern;
    }
    
    
}