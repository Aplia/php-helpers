<?php
namespace Aplia\Support;

/**
 * Helper functions for dealing with path manipulation.
 */
class Path
{
    /**
     * Join all path elements into a single path string.
     * 
     * ```php
     * // Returns "var/storage"
     * Path::join(["var", "storage]);
     * ```
     * 
     * ```php
     * // An empty array returns ""
     * Path::join([]);
     * ```

     * ```php
     * // Using a root returns "/var/www/var/storage"
     * Path::join(["var", "storage], "/var/www");
     * ```
     * 
     * @param  string|array $elements Path elements
     * @param  string       $root     Optional root path to place in front of joined path
     * @return string
     */
    public static function join($elements, $root = null)
    {
        if (!is_array($elements)) {
            $elements = [$elements];
        }
        return join(
            DIRECTORY_SEPARATOR,
            array_map(function ($e) {
                return rtrim($e, DIRECTORY_SEPARATOR);
            }, array_merge(!$root || substr($elements[0], 0, 1) == '/' ? [] : [$root], $elements))
        );
    }

    /**
     * Make a path from all arguments by joining them using ::join() and return it.
     *
     * ```php
     * // Returns "var/storage"
     * Path::make("var", "storage")
     * ```
     *
     * ```php
     * // Returns ""
     * Path::make()
     * ```
     *
     * @return string
     */
    public static function make()
    {
        $elements = func_get_args();
        return self::join($elements);
    }
}
