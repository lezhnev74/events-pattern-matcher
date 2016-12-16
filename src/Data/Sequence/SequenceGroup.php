<?php

namespace Lezhnev74\EventsPatternMatcher\Data\Sequence;

/**
 * Contains many sequences (grouped by external logic)
 *
 * For example sequences of different user_id within the same time period (all sequences for each user_id within given
 * week)
 *
 * @package Lezhnev74\EventsPatternMatcher\Data\Sequence
 */
class SequenceGroup
{
    /** @var array */
    private $sequences = [];
    /** @var  string */
    private $title;
    
    /**
     * SequenceGroup constructor.
     *
     * @param array  $sequences
     * @param string $title
     */
    public function __construct(array $sequences, $title)
    {
        $this->sequences = $sequences;
        $this->title     = $title;
        
        foreach ($sequences as $sequence) {
            if (!is_a($sequence, Sequence::class)) {
                throw new \InvalidArgumentException("Expected Sequence object but detected one is: " . get_class($sequence));
            }
        }
    }
    
    /**
     * @return array
     */
    public function getSequences(): array
    {
        return $this->sequences;
    }
    
    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }
    
}