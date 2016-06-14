# CSS Compiler
* can be triggered from composer's script's session: compiles LESS/SASS/Compass

# How to use:
1. add into composer __scripts__ directory:
```
"EM\\CssCompiler\\Handler\\ScriptHandler::compileCSS"
```

example: 
```
    "scripts": {
        "post-update-cmd": "@custom-events",
        "post-install-cmd": "@custom-events",
        "custom-events": [
            "EM\\CssCompiler\\Handler\\ScriptHandler::compileCSS"
        ]
    },
```
2. add _css-compiler_ information inside of the _extra_ composer configuration
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

#legend
    _format_: compression format
    _input_: array of routes, all files inside of the route if it is directory will be picked up
    _output_: file where it should put content (hard-copy)
