<?php
namespace Aplia\Support\Traits;

/**
 * A trait which gives the class support for eZ publish template attributes.
 * The template attribute system existed before the PHP magic methods `__get()` etc
 * existed so it had to make its own system.
 *
 * By using this trait the classes will allow instances to be used in eZ publish
 * templates. The attributes will map to the existing properties or virtual
 * properties on the class.
 *
 * The following new methods will be available:
 *
 * - `hasAttribute()` (similar to `__isset()`)
 * - `attributes()` (no PHP equivelant, uses `__properties()`)
 * - `attribute()` (similar to `__get()`)
 * - `setAttribute()` (similar to `__set()`)
 *
 * @since 1.1
 */
trait TemplateAttributes
{
    /**
     * Checks if the attribute named $name exists and returns true or false.
     *
     * @param string $name Name of attribute, e.g. 'id'
     * @return bool True if it exists, false otherwise
     */
    public function hasAttribute($name)
    {
        return isset($this->$name);
    }

    /**
     * Returns an array with all properties (regular and virtual) that exists on
     * the instance.
     *
     * Note: This requires that the class has defined the `__properties()` method.
     *
     * @return string[] Array of property names, e.g. ['id', 'tableSize']
     */
    public function attributes()
    {
        return $this->__properties();
    }

    /**
     * Returns the value of the property named $name.
     *
     * @param string $name Name of attribute, e.g. 'id'
     * @return mixed The property value or null if the property does not exist
     */
    public function attribute($name)
    {
        if (isset($this->$name)) {
            return $this->$name;
        }
    }

    /**
     * Sets the value of the property named $name.
     *
     * Note: Certain virtual properties may not support
     * seting the value, this may result in an exception.
     *
     * @param string $name Name of attribute, e.g. 'id'
     * @param mixed $v The property value to set.
     * @return void
     */
    public function setAttribute($name, $v)
    {
        $this->$name = $v;
    }
}
