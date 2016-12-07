# arc-tsclint

Use [tsc](https://www.typescriptlang.org/) to build your Typescript source code with
[Phabricator](http://phabricator.org)'s `arc` command line tool.

## Features

tsclint generates error messages.

Example output:

    >>> Lint for src/index.ts:
    
     Error  (TS2314) tsc failure
      Generic type 'Observable<T>' requires 1 type argument(s).
      
                16 
                17 import $$observable from 'symbol-observable';
                18 
      >>>       19 export default class IndefiniteObservable<T> implements Observable {
                20   _creator: Creator;
                21 
                22   constructor(creator: Creator) {

## Installation

tsc is required.

    npm install typescript -g

### Project-specific installation

You can add this repository as a git submodule. Add a path to the submodule in your `.arcconfig`
like so:

```json
{
  "load": ["path/to/arc-tsclint"]
}
```

### Global installation

`arcanist` can load modules from an absolute path. But it also searches for modules in a directory
up one level from itself.

You can clone this repository to the same directory where `arcanist` and `libphutil` are located.
In the end it will look like this:

```sh
arcanist/
arc-tsclint/
libphutil/
```

Your `.arcconfig` would look like

```json
{
  "load": ["arc-tsclint"]
}
```

## Setup

To use the linter you must register it in your `.arclint` file.

```json
{
  "linters": {
    "ts": {
      "type": "tsclint",
      "include": "(src/.*\\.(ts)$)"
    }
  }
}
```

## License

Licensed under the Apache 2.0 license. See LICENSE for details.
