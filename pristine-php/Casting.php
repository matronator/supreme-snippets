<?php

declare(strict_types=1);

class Casting
{
    /**
     * Translates type
     * @param $destination Object destination
     * @param stdClass $source Source
     */
    public static function Cast(&$destination, stdClass $source)
    {
        $sourceReflection = new \ReflectionObject($source);
        $sourceProperties = $sourceReflection->getProperties();
        foreach ($sourceProperties as $sourceProperty) {
            $name = $sourceProperty->getName();
            if (gettype($destination->{$name}) == "object") {
                self::Cast($destination->{$name}, $source->$name);
            } else {
                $destination->{$name} = $source->$name;
            }
        }
    }
}
