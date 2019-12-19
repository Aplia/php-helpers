# PHP helper classes and functions

This package contains extra support classes and functions for making it
easier to work with PHP. It implements some features which are missing
from PHP but which are commonly required, for instance array and path
manipulation.

All classes are placed under the namespace `Aplia\Support`, for instance:

```php
use Aplia\Support\Arr;

Arr::get($array, 'key');
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
