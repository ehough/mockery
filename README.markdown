## mockery

[![Build Status](https://secure.travis-ci.org/ehough/mockery.png)](http://travis-ci.org/ehough/mockery)
[![Project Status: Unsupported - The project has reached a stable, usable state but the author(s) have ceased all work on it. A new maintainer may be desired.](http://www.repostatus.org/badges/latest/unsupported.svg)](http://www.repostatus.org/#unsupported)
[![Latest Stable Version](https://poser.pugx.org/ehough/mockery/v/stable)](https://packagist.org/packages/ehough/mockery)
[![License](https://poser.pugx.org/ehough/mockery/license)](https://packagist.org/packages/ehough/mockery)

**This library is no longer supported or maintained as PHP 5.2 usage levels have finally dropped below 10%**

Fork of [padraic/mockery](https://github.com/padraic/mockery) compatible with PHP 5.2+.

### Motivation

[padraic/mockery](https://github.com/padraic/mockery) is a fantastic mocking library, but it's only compatible with
PHP 5.3+. While 99% of PHP servers run PHP 5.2 or higher, 12% of all servers are still running PHP 5.2 or lower
([source](http://w3techs.com/technologies/details/pl-php/5/all)).

### Differences from [padraic/mockery](https://github.com/padraic/mockery)

The primary difference is naming conventions of the [padraic/mockery](https://github.com/padraic/mockery) classes.
Instead of the `\Mockery` namespace (and sub-namespaces), prefix the class names
with `ehough_mockery` and follow the [PEAR naming convention](http://pear.php.net/manual/en/standards.php)

A few examples of class naming conversions:

    \Mockery              ----->    ehough_mockery_Mockery
    \Mockery\Mock         ----->    ehough_mockery_mockery_Mock
    \Mockery\Matcher\Any  ----->    ehough_mockery_mockery_matcher_Any

### Usage

Visit [padraic/mockery](https://github.com/padraic/mockery) for the current documentation.

### Releases and Versioning

Releases are synchronized with the upstream padraic repository. e.g. `ehough/mockery 0.8.0` has merged the code
from `padraic/mockery 0.8.0`.
