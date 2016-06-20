[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/learn-symfony/css-compiler/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/learn-symfony/css-compiler/?branch=master)

[![Latest Stable Version](https://poser.pugx.org/eugene-matvejev/css-compiler/version)](https://packagist.org/packages/eugene-matvejev/css-compiler)
[![Total Downloads](https://poser.pugx.org/eugene-matvejev/css-compiler/downloads)](https://packagist.org/packages/eugene-matvejev/css-compiler)
[![License](https://poser.pugx.org/eugene-matvejev/css-compiler/license)](https://packagist.org/packages/eugene-matvejev/css-compiler)
[![composer.lock](https://poser.pugx.org/eugene-matvejev/css-compiler/composerlock)](https://packagist.org/packages/eugene-matvejev/css-compiler)


# PHP CSS Compiler
_can be triggered from composer's script's section: compiles LESS|SASS|Compass_

# How to use:
```
composer require "eugene-matvejev/css-compiler"
```
if you have problem with min-stability you can use this solution in '_require(-dev)_':
_example_:
```
"require": {
    "eugene-matvejev/css-compiler": "^0.1",
    "leafo/scssphp-compass": "@dev",
    "leafo/scssphp": "@dev"
}
```

### add callback into into composer's __scripts__:
```
"EM\\CssCompiler\\Handler\\ScriptHandler::compileCSS"
```
_example_:
```
"scripts": {
    "post-update-cmd": "@custom-events",
    "post-install-cmd": "@custom-events",
    "custom-events": [
        "EM\\CssCompiler\\Handler\\ScriptHandler::compileCSS"
    ]
}
```
### add _css-compiler_ information inside of the _extra_ composer configuration
 * _format_: compression format
 * _input_: array of relative paths to the composer.json, all files will be picked up recursivly inside of the directory
 * _output_:  relative file path to the composer.json, where to save output (hard-copy)

_example_:
```
"extra": {
    "css-compiler": [
        {
            "format": "compact",
            "input": [
                "tests/shared-fixtures/scss"
            ],
            "output": "var/cache/assets/scss.css"
        },
        {
            "format": "compact",
            "input": [
                "tests/shared-fixtures/sass"
            ],
            "output": "var/cache/assets/sass.css"
        },
        {
            "format": "compact",
            "input": [
                "tests/shared-fixtures/compass/app.scss"
            ],
            "output": "var/cache/assets/compass.css"
        }
    ]
}
```
