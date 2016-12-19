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
    
    /**
     *
     * @param $resolveGroupNameFromSequenceGroup    is a callable which recieves a sequence and must return a name of
     *                                              the group this event should belong to. False response means I want
     *                                              to exclude this sequence from results at all.
     *
     * @return array of SequenceGroup objects
     */
    public function splitInGroups(callable $resolveGroupNameFromSequenceGroup): array
    {
        
        //
        // Fill up the groups array (each sequence will belong to one group only)
        //
        $groups = [];
        foreach ($this->getSequences() as $sequence) {
            $group_name = $resolveGroupNameFromSequenceGroup($sequence);
            
            if ($group_name === false) {
                // exclude this sequence from grouping
                continue;
            }
            
            if (!isset($groups[$group_name])) {
                $groups[$group_name] = [];
            }
            
            $groups[$group_name][] = $sequence;
        }
        
        //
        // Now make sequence groups from arrays
        //
        foreach ($groups as $group_name => $sequences) {
            $groups[$group_name] = new SequenceGroup($sequences, $group_name);
        }
        
        return $groups;
    }
}