<?php
namespace Aplia\Support\Traits;

use Aplia\Support\PropertyHelper;
use Aplia\Support\Exceptions\PropertyError;
use Aplia\Support\Exceptions\PropertyReadOnly;

/**
 * A trait which makes it easier to create classes with virtual properties.
 * Virtual properties are properties that doesn't exist on the object but are
 * bound to a function that gets called when the property name is accessed on
 * the object. This is all done using the PHP magic methods `__get()`, `__isset()`,
 * `__set()` and `__unset()`.
 *
 * In addition it also provides a new magic method `__properties()` which returns
 * an array with all properties on the object, including virtual ones.
 *
 * Define virtual properties by creating methods that starts with **prop** or **cached**
 * and the property name in *CamelCase*.
 * For instance `propName()` becomes the property **name** and `cachedTableSize()`
 * becomes *tableSize*.
 *
 * Methods starting with **cached** are executed once and then cached for later
 * retrieval. So a method named `cachedTableSize()` will result
 * in a regular property named `tableSize` to be set on the object, the next it
 * is accessed PHP will fetch the regular property. To remove the caching use
 * unset on the property, e.g. `unset($obj->tableSize)`, this will remove the
 * regular property and force the method to be called again.
 *
 * Virtual properties cannot be set unless they have a method to handle this
 * operation, create a method with the prefix **setprop**, e.g. **setpropId**.
 *
 * Virtual properties cannot be unset unless they have a method to handle this
 * operation, create a method with the prefix **unsetprop**, e.g. **unsetpropId**.
 *
 * ## Example
 *
 * Example of a class using all functionality, it will have the following properties:
 *
 * - *name* - regular
 * - *id* - virtual and read-only
 * - *version* - virtual
 * - *code* - virtual and cached
 *
 * ```php
 * /**
 *  * @property-read string $id
 *  * @property string $version
 *  * @property string $code
 *  *\/
 * class Topic
 * {
 *     use \Aplia\Support\Traits\VirtualProperties;
 *
 *     public $name;
 *     protected $_id;
 *     protected $_version;
 *
 *     public function __construct($id, $version, $name)
 *     {
 *         $this->_id = $id;
 *         $this->_version = $version;
 *         $this->name = $name;
 *     }
 *
 *     public function cachedCode()
 *     {
 *         return $this->version !== null ? ($this->_id . '-' . $this->version) : $this->_id;
 *     }
 *
 *     public function propId($id)
 *     {
 *         return $this->_id;
 *     }
 *
 *     public function propVersion()
 *     {
 *         return $this->_version;
 *     }
 *
 *     public function setpropVersion($version)
 *     {
 *         $this->_version = $version;
 *     }
 *
 *     public function unsetpropVersion()
 *     {
 *         $this->_version = null;
 *     }
 * }
 *
 * $t = new Topic('english', '1', 'foo');
 * $t->name; // returns 'foo' (regular property)
 * $t->id; // returns 'english'
 * $t->id = 'greek'; // *id* is read-only so this fails
 * $t->version; // returns '1'
 * $t->code; // returns 'english-1'
 * $t->version = '2';
 * $t->code; // returns 'english-2'
 * unset($t->version);
 * $t->code; // returns 'english'
 * ```
 *
 * @since 1.1
 */
trait VirtualProperties
{
    /**
     * Cache for property names, one entry per class.
     *
     * e.g.
     *
     * [
     *   'Uia\Plans\StudyPlan' => ['id', 'year']
     * ]
     *
     * @var array
     */
    protected static $BaseModelAttributes = null;
    /**
     * Cache storage for cached properties, one entry per property.
     *
     * If a virtual property has a __cached*__ method the result of the
     * method will stored in this cache storage for subsequent lookups.
     *
     * @var array
     */
    protected $BaseModelCacheStorage = [];

    /**
     * Look up virtual properties on the object and returns true if it exists, false otherwise.
     *
     * @param string $name Name of property
     * @return bool
     */
    public function __isset($name)
    {
        $propName = ucfirst($name);
        return method_exists($this, 'prop' . $propName) || method_exists($this, 'cached' . $propName);
    }

    /**
     * Return the value of a virtual properties on the object.
     *
     * @param string $name Name of property
     * @return mixed
     * @throws PropertyError If the property does not exist
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->BaseModelCacheStorage)) {
            return $this->BaseModelCacheStorage[$name];
        }
        $propName = ucfirst($name);
        $func = 'prop' . $propName;
        if (method_exists($this, $func)) {
            return $this->$func();
        }
        $func = 'cached' . $propName;
        if (method_exists($this, $func)) {
            return $this->BaseModelCacheStorage[$name] = $this->$func();
        }
        throw new PropertyError('No such property: ' . $name);
    }

    /**
     * Set the value of a virtual property on the object.
     * The virtual property must have a method to set the value, the method
     * must start with **setprop**, e.g. **setpropId** and will receive the
     * value as the first parameter.
     *
     * @param string $name Name of property
     * @param mixed $v The value for the property
     * @return void
     * @throws PropertyError If the property does not exist
     * @throws PropertyReadOnly If the property is read-only
     */
    public function __set($name, $v)
    {
        $propName = ucfirst($name);
        $func = 'setprop' . $propName;
        if (method_exists($this, $func)) {
            return $this->$func($v);
        }
        if (isset($this->$name)) {
            throw new PropertyReadOnly('Read-only property, cannot set: ' . $name);
        }
    }

    /**
     * Unset value for a virtual property.
     * This is only works for cached virtual properties or properties which
     * has a method to handle the unset, the method must start with
     * **unsetprop**, e.g. **unsetpropId**.
     *
     * @param string $name Name of property, e.g. 'id'
     * @return void
     */
    public function __unset($name)
    {
        $propName = ucfirst($name);
        $func = 'unsetprop' . $propName;
        if (method_exists($this, $func)) {
            $this->$func();
            return;
        }
        $func = 'prop' . $propName;
        if (method_exists($this, $func)) {
            throw new PropertyError('Cannot unset virtual property: ' . $name);
        }
        $func = 'cached' . $propName;
        if (method_exists($this, $func)) {
            unset($this->BaseModelCacheStorage[$name]);
            return;
        }
        // Ignore all other properties
    }

    /**
     * Looks up all properties (regular and virtual) on the object and an array with their names.
     *
     * e.g. if a class has the property `$name` and methods `propId()` and `propTableSize()` it returns
     * `['name', 'id', 'tableSize']`
     *
     * @return string[]
     */
    public function __properties()
    {
        $cname = get_class($this);
        if (self::$BaseModelAttributes === null || !isset(self::$BaseModelAttributes[$cname])) {
            // Note: Uses external helper to ensure only public vars are found
            $attrs = array_keys(PropertyHelper::getPublicProperties($this));
            foreach (get_class_methods($this) as $name) {
                if (substr($name, 0, 4) === 'prop') {
                    $attrs[] = strtolower($name[4]) . substr($name, 5);
                } elseif (substr($name, 0, 6) === 'cached') {
                    $attrs[] = strtolower($name[6]) . substr($name, 7);
                }
            }
            if (self::$BaseModelAttributes === null) {
                self::$BaseModelAttributes = [];
            }
            self::$BaseModelAttributes[$cname] = $attrs;
            return $attrs;
        } else {
            return self::$BaseModelAttributes[$cname];
        }
    }

    /**
     * Removes the cache value for the virtual cached property $name
     * Can be used internally by classes to clear up cached values.
     *
     * @param string $name Name of property, e.g. 'id'
     * @return void
     */
    protected function unsetCachedProperty($name)
    {
        unset($this->BaseModelCacheStorage[$name]);
    }
}
