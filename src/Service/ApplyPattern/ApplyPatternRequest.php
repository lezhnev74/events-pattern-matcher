<?php

namespace Lezhnev74\EventsPatternMatcher\Service\ApplyPattern;


use Lezhnev74\EventsPatternMatcher\Data\Pattern\Pattern;
use Lezhnev74\EventsPatternMatcher\Data\Sequence\Sequence;

class ApplyPatternRequest
{
    /**
     * @var Pattern
     */
    private $pattern;
    /**
     * @var Sequence
     */
    private $sequence;
    
    public function __construct(Pattern $pattern, Sequence $sequence)
    {
        $this->pattern  = $pattern;
        $this->sequence = $sequence;
    }
    
    public function getPattern(): Pattern
    {
        return $this->pattern;
    }
    
    public function getSequence(): Sequence
    {
        return $this->sequence;
    }
    
    
}