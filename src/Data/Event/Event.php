<?php
namespace Lezhnev74\EventsPatternMatcher\Data\Event;


use Carbon\Carbon;

class Event
{
    private $name;
    /** @var Carbon */
    private $occured_at;
    
    public function __construct($name, string $occured_at)
    {
        $this->name = $name;
        try {
            $this->occured_at = is_numeric($occured_at) ? Carbon::createFromTimestamp($occured_at) : Carbon::parse($occured_at);
        } catch (\Exception $e) {
            throw new BadEvent("Invalid occured_at input for event: " . $occured_at . ". Message:" . $e->getMessage());
        }
    }
    
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * @return Carbon
     */
    public function getOccuredAt(): Carbon
    {
        return $this->occured_at;
    }
    
    
    /**
     * Make object from json config
     *
     * @param array $event
     */
    static function fromArray(array $event)
    {
        //
        // All the config is described with this pattern
        //
        $pattern = [
            "name"        => ":string min(1)",
            "occurred_at" => ":scalar min(1)",
        ];
        
        try {
            $valid = \matchmaker\catches($event, $pattern);
        } catch (\Exception $e) {
            throw new BadEvent("Invalid config given for this event: " . $e->getMessage());
        }
        
        
        //
        // Ok, all good - make an object from config
        //
        return new self($event['name'], $event['occurred_at']);
    }
}