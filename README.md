PHPUnit Focus
=============

PHPUnit has a powerful interface for filtering which tests are run, but that
interface does not lend itself to easy integration with development tools.
Computers and scripts think in terms of files, lines, and bytes, not PHP
namespaces, classes, and methods.

PHPUnit Focus lets you narrow in to run a single test at a time given the file
name and a line number within that file and is largely inspired by the same
feature provided by [RSpec](https://www.relishapp.com/rspec/rspec-core/v/3-3/docs/command-line/line-number-appended-to-file-path).

## Installation

1. Clone this repository
2. Run `composer install`

## Usage

The interface of the program is straightforward:

    bin/focus <path-to-phpunit> <file> <line> [<phpunit args...>]

For example, given the file SampleTest.php with the following contents:

```php
<?php

class SampleTest extends PHPUnit_Framework_TestCase
{
    public function testOne()
    {
        $this->assertTrue(true);
    }
}
```

...running the following command...

    bin/focus phpunit SampleTest.php 7

...will run only `SampleTest::testOne`.

## To Do

- Vim dotfiles example
