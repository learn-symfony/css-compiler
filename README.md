# CSS Compiler
* can be triggered from composer's script's session: compiles LESS/SASS/Compass

# How to use:
```
composer require "eugene-matvejev/css-compiler"
```
### add into composer __scripts__ directory:
```
"EM\\CssCompiler\\Handler\\ScriptHandler::compileCSS"
```
if you have problem with min-stability you can use this solution in '_require_' or '_require-dev_':
```
    "eugene-matvejev/css-compiler": "^0.1",
    "leafo/scssphp-compass": "@dev",
    "leafo/scssphp": "@dev",
```

_example_:
```
    "scripts": {
        "post-update-cmd": "@custom-events",
        "post-install-cmd": "@custom-events",
        "custom-events": [
            "EM\\CssCompiler\\Handler\\ScriptHandler::compileCSS"
        ]
    },
```
### add _css-compiler_ information inside of the _extra_ composer configuration
 * _format_: compression format
 * _input_: array of routes, all files inside of the route if it is directory will be picked up
 * _output_: file where it should put content (hard-copy)

_example_:
```
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
```
