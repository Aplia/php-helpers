# PHP support classes and functions

This package contains extra support classes and functions for making it
easier to work with PHP. It implements some features which are missing
from PHP but which are commonly required, for instance array and path
manipulation.

All classes are placed under the namespace `Aplia\Support`, using them
simply requires using this namespace.

```php
use Aplia\Support;

Arr::get($array, 'key');
```

## Arr

Various functionality for working with arrays. These are mostly taken
from the Laravel framework.

## Path

Path manipulation such as joining multiple files/dir names into one
path.

## LoggerAdapter

Adapts the PSR logger interface to eZ debug.

## Val

Enforeces a variable to a simple value, closures are called to get
the real value.
