# mockery [![Build Status](https://secure.travis-ci.org/ehough/mockery.png)](http://travis-ci.org/ehough/mockery)

Fork of [padraic/mockery](https://github.com/padraic/mockery) compatible with PHP 5.2+. Fully tested and frequently merged with upstream changes from [padraic/mockery](https://github.com/padraic/mockery).

### Motivation

`padraic/mockery` is a fantastic mocking library, but it's only compatible with PHP 5.3+. While 97% of PHP servers run PHP 5.2 or higher,
a whopping **47% of all servers are still running PHP 5.2** ([source](http://w3techs.com/technologies/details/pl-php/5/all)).
It would be a shame to exempt this library from nearly half of the world's servers just because of a few version incompatibilities.

Once PHP 5.3+ adoption level near closer to 100%, this library will be retired.

### Differences from [padraic/mockery](https://github.com/padraic/mockery)

The primary difference is naming conventions of the `padraic/mockery` classes.
Instead of the `\Mockery` namespace (and sub-namespaces), prefix the `padraic/mockery` class names
with `ehough_mockery` and follow the [PEAR naming convention](http://pear.php.net/manual/en/standards.php)

A few examples of class naming conversions:

    \Mockery              ----->    ehough_mockery_Mockery
    \Mockery\Mock         ----->    ehough_mockery_mockery_Mock
    \Mockery\Matcher\Any  ----->    ehough_mockery_mockery_matcher_Any

### Usage

Visit [padraic/mockery](https://github.com/padraic/mockery) for the current documentation.
