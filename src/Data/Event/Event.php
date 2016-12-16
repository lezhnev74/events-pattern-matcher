<?php
namespace Lezhnev74\EventsPatternMatcher\Data\Event;


class Event
{
    private $name;
    
    public function __construct($name) { $this->name = $name; }
    
    public function getName()
    {
        return $this->name;
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
            "occurred_at" => ":date",
        ];
        
        $valid = \matchmaker\catches($event, $pattern);
        if (!$valid) {
            throw new BadEvent("Invalid config given for this event");
        }
        
        //
        // Ok, all good - make an object from config
        //
        return new self($event['name']);
    }
}