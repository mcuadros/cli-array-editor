CLIArrayEditor [![Build Status](https://travis-ci.org/mcuadros/cli-array-editor.png?branch=master)](https://travis-ci.org/mcuadros/cli-array-editor)
==============================

This library provides an easy way to edit arrays in CLI using vim or your preferred editor.

Can be useful in scenarios where we need an interface to edit arrays from a CLI PHP application, CLIArrayEditor will open an editor with the array in a readable format (json, yaml, etc) and wait for the user (like "crontab -e" or "git commit"). 

The editor can be configured or by default $EDITOR from the environment will be used.

Requirements
------------

* PHP 5.3.23;
* Unix system;
* PECL yaml >= 0.5.0 (optional)


Installation
------------

The recommended way to install CLIArrayEditor is [through composer](http://getcomposer.org).
You can see [package information on Packagist.](https://packagist.org/packages/mcuadros/cli-array-editor)

```JSON
{
    "require": {
        "mcuadros/cli-array-editor": "dev"
    }
}
```


Examples
--------

```php
use CLIArrayEditor\Editor;
use CLIArrayEditor\Format\JSON;

$tmp = array(
    'baz' => true,
    'foo' => 'bar'
);

$editor = new Editor();
$result = $editor
    ->setFormat(new JSON)
    ->edit($tmp);

print_r($result);
```

Check available Editor's methods in [src/CLIArrayEditor/Editor.php](src/CLIArrayEditor/Editor.php)

Tests
-----

Tests are in the `tests` folder.
To run them, you need PHPUnit.
Example:

    $ phpunit --configuration phpunit.xml.dist


License
-------

MIT, see [LICENSE](LICENSE)