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

function search($query, $params=null)
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
Path::join(["var", "storage"]);
// "var/storage"
```

```php
Path::join([]);
// ""
```

```php
// Using a root
Path::join(["var", "storage"], "/var/www");
// "/var/www/var/storage"
```

```php
Path::make("vendor", "composer");
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
Val::value(function () { return 5; }) === 5;
```

## License

The helper library is open-sourced software licensed under the MIT license.
