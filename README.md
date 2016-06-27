[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/learn-symfony/css-compiler/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/learn-symfony/css-compiler/?branch=master)
[![codecov](https://codecov.io/gh/learn-symfony/css-compiler/branch/master/graph/badge.svg)](https://codecov.io/gh/learn-symfony/css-compiler)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/b72078dc-94a7-492f-9deb-3829c41d2519/mini.png)](https://insight.sensiolabs.com/projects/b72078dc-94a7-492f-9deb-3829c41d2519)

[![HHVM Status](http://hhvm.h4cc.de/badge/eugene-matvejev/css-compiler.svg)](http://hhvm.h4cc.de/package/eugene-matvejev/css-compiler)

[![Latest Stable Version](https://poser.pugx.org/eugene-matvejev/css-compiler/version)](https://packagist.org/packages/eugene-matvejev/css-compiler)
[![Total Downloads](https://poser.pugx.org/eugene-matvejev/css-compiler/downloads)](https://packagist.org/packages/eugene-matvejev/css-compiler)
[![License](https://poser.pugx.org/eugene-matvejev/css-compiler/license)](https://packagist.org/packages/eugene-matvejev/css-compiler)
[![composer.lock](https://poser.pugx.org/eugene-matvejev/css-compiler/composerlock)](https://packagist.org/packages/eugene-matvejev/css-compiler)


# PHP CSS Compiler
_can be triggered from composer's script's section: compiles SCSS with compass|LESS_

# How to use:
```
composer require "eugene-matvejev/css-compiler"
```

### add callback into into composer's __scripts__:
```
"EM\\CssCompiler\\ScriptHandler::generateCSS"
```
_example_:
```
"scripts": {
    "post-update-cmd": "@custom-events",
    "post-install-cmd": "@custom-events",
    "custom-events": [
        "EM\\CssCompiler\\ScriptHandler::generateCSS"
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
                "tests/shared-fixtures/compass/app.scss"
            ],
            "output": "var/cache/assets/compass.css"
        },
        {
            "format": "compact",
            "input": [
                "tests/shared-fixtures/sass"
            ],
            "output": "var/cache/assets/sass.css"
        }
    ]
}
```
