# WPK Core CLI

Command line interface for [wpk-core](https://github.com/TheUnderScorer/wpk-core) library.
Note: It is WIP :).

Installation: `composer global require the-under-scorer/wpk-core-cli`

Usage: `wpk-core command [options]`

| Command   | Description   | Options  | Example |
|---|---|---|---|
| app:install  |  Installs plugin files into provided directory  | directory: string = Directory to which plugin files will be installed  | wpk-core app:install my-plugin
| module:make  |  Creates module directory  | name:string = Name of created module | wpk-core module:make Test
