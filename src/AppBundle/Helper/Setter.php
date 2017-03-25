<?php
namespace AppBundle\Helper;

class Setter
{
    public static function set($sourceObject, $targetObject, $properties, $targetPrefix = "")
    {
        if(!is_array($properties)) {
            $properties = explode(",",$properties);
        }
        foreach ($properties as $key=>$value) {
            if(is_int($key)) {
                $targetSetter = "set".$targetPrefix.ucfirst($value);
                $sourceProperty = $value;
            } else {
                $targetSetter = $key;
                $sourceProperty = $value;
            }
            if(is_object($sourceObject) && property_exists($sourceObject,$sourceProperty)) {
                call_user_func([$targetObject, $targetSetter], $sourceObject->$sourceProperty);
            } elseif(is_array($sourceObject) && array_key_exists($sourceProperty,$sourceObject)) {
                call_user_func([$targetObject, $targetSetter], $sourceObject[$sourceProperty]);
            } else {
                call_user_func([$targetObject, $targetSetter], null);
            }
        }
    }
}