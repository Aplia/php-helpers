# PHP helper classes and functions

This package contains extra support classes and functions for making it
easier to work with PHP. It implements some features which are missing
from PHP but which are commonly required, for instance array and path
manipulation.

[![Latest Stable Version](https://img.shields.io/packagist/v/aplia/support.svg?style=flat-square)](https://packagist.org/packages/aplia/support)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%205.3-8892BF.svg?style=flat-square)](https://php.net/)

All classes are placed under the namespace `Aplia\Support`, for instance:

```php
use Aplia\Support\Arr;

Arr::get($array, 'key');
```

## Installation

Install with Composer:

```
composer require aplia/support
```

## Arr

Various functionality for working with arrays. These are mostly taken
from the Laravel framework and placed in this repository to avoid
extra dependencies.

### Arr::get

```php
use Aplia\Support\Arr;

$array = ['products' => ['desk' => ['price' => 100]]];

$price = Arr::get($array, 'products.desk.price');

// 100
```

A typical use case is to support keyword arguments to a function
by passing an array as a parameter. `Arr::get` can then be used
to easily fetch a parameter if it is set or use a default value.

```php
use Aplia\Support\Arr;

function search($query, $params = null)
{
    $limit = Arr::get($params, 'limit');
    $fields = Arr::get($params, 'fields', 1);
    // ...
}
```

## Path

Path manipulation such as joining multiple files/dir names into one
path.

```php
Path::join(['var', 'storage']);
// "var/storage"
```

```php
Path::join([]);
// ""
```

```php
// Using a root
Path::join(['var', 'storage'], '/var/www');
// "/var/www/var/storage"
```

```php
Path::make('vendor', 'composer');
// "vendor/composer"
```

## LoggerAdapter

Adapts the PSR logger interface to eZ debug, add this as a handler to
any PSR log. If the class `eZDebug` exists it will pass log messages
along.

## Val

Enforces a variable to a simple value, closures are called to get
the real value.

```php
use Aplia\Support\Val;

// Any regular value is simply returned
Val::value(5) === 5;

// Closures are called to fetch the actual value
Val::value(function () {
    return 5;
}) === 5;
```

## VirtualProperties

> Available since: 1.1

A trait which makes it easier to create classes with virtual properties.

Virtual properties are properties that doesn't exist on the object but are
bound to a function that gets called when the property name is accessed on
the object. This is all done using the PHP magic methods `__get()`, `__isset()`,
`__set()` and `__unset()`.

If the class extends a base class and that class also implements `__isset()` etc.
VirtualProperties will make sure they are called as part of the checks.

If `__baseProperties()` exists it will use this to add in extra properties
when `__properties()` is called.

Property lookup is strict by default and will throw `PropertyError` for
missing properties in `__get()`, to disable strictness reimplement the
method `__requireProperties()` and make it return `false`.

Example of a class using all functionality, it will have the following properties:

-   _name_ - regular
-   _id_ - virtual and read-only
-   _version_ - virtual
-   _code_ - virtual and cached

```php
/**
 * @property-read string $id
 * @property string $version
 * @property string $code
 *\/
class Topic
{
    use \Aplia\Support\Traits\VirtualProperties;

    public $name;
    protected $_id;
    protected $_version;

    public function __construct($id, $version, $name)
    {
        $this->_id = $id;
        $this->_version = $version;
        $this->name = $name;
    }
    public function cachedCode()
    {
        return $this->version !== null ? ($this->_id . '-' . $this->version) : $this->_id;
    }
    public function propId($id)
    {
        return $this->_id;
    }
    public function propVersion()
    {
        return $this->_version;
    }

    public function setpropVersion($version)
    {
        $this->_version = $version;
    }
    public function unsetpropVersion()
    {
        $this->_version = null;
    }
}

$t = new Topic('english', '1', 'foo');
$t->name; // returns 'foo' (regular property)
$t->id; // returns 'english'
$t->id = 'greek'; // *id* is read-only so this fails
$t->version; // returns '1'
$t->code; // returns 'english-1'
$t->version = '2';
$t->code; // returns 'english-2'
unset($t->version);
$t->code; // returns 'english'
```

## TemplateAttributes

> Available since: 1.1

A trait which gives the class support for eZ publish template attributes.

By using this trait the classes will allow instances to be used in eZ publish
templates. The attributes will map to the existing properties or virtual
properties on the class.

Works best when combined with `VirtualPropertes`.

## License

The helper library is open-sourced software licensed under the MIT license.
