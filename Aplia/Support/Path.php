<?php
namespace Aplia\Support;

/**
 * Path manipulation.
 */
class Path
{
    public function join($elements, $root=null)
    {
        if (!is_array($elements)) {
            $elements = array($elements);
        }
        return join(
            DIRECTORY_SEPARATOR,
            array_map(
                function ($e) {
                    return rtrim($e, DIRECTORY_SEPARATOR);
                },
                array_merge((!$root || substr($elements[0], 0, 1) == '/') ? array() : array($root), $elements)
            )
        );
    }

    public function make()
    {
        $elements = func_get_args();
        return self::join($elements);
    }
}
