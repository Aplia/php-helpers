<?php
namespace Aplia\Support;

/* Copy of support functions from Laravel */

class Val
{
    /**
     * Return the actual value from $value. Closures are called
     * to return the value, while other types are simply returned as-is.
     *
     * 
     * ```php
     * // Any regular value is simply returned
     * Val::value(5) === 5;
     * 
     * // Closures are called to fetch the actual value
     * Val::value(function () { return 5; }) === 5;
     * ```
     * 
     * @param  mixed  $value
     * @return mixed
     */
    public static function value($value)
    {
        return $value instanceof \Closure ? $value() : $value;
    }
}
