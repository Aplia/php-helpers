<?php
namespace Aplia\Support;

/**
 * Class with static methods for helping out with class properties.
 *
 * @since 1.1
 */
class PropertyHelper
{
    /**
     * Returns an array with all public properties on an object.
     *
     * Note: This function is basically the same as `get_object_vars()`, the difference
     * is that when using `get_object_vars()` inside its own class will return all
     * properties, not just public ones. By placing the call in a totally separate
     * class like this it ensures only public properties are available.
     *
     * @param object $object
     * @return array
     */
    public static function getPublicProperties($object)
    {
        return get_object_vars($object);
    }
}
