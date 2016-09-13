<img alt="Taco Theme Logo" src="https://raw.githubusercontent.com/tacowordpress/taco-theme/master/src/logo-taco-theme.gif" width="300">
---
# Taco Theme
> _Always start with a shell._ | A bare theme for WordPress that uses [tacowordpress](https://github.com/tacowordpress/tacowordpress)

Installing tacowordpress into the theme can be done in 1 of 2 ways,

1. using the [taco-installer](https://github.com/tacowordpress/taco-installer), or
2. manually installing this Taco Theme to a WordPress instance by following the instructions below:

## Manually Installing Taco Theme to a WordPress instance
_without the taco-installer_

## Requirements
* [Composer](https://getcomposer.org/)

## Installing / Getting started

With a WordPress instance already setup, create a new directory in the theme and call it taco-theme. Download this taco-theme, and copy/paste all the contents from /src into the new WordPress taco-theme directory.

Next,

cd into the taco-theme's `/app/core` directory, and run `composer install`

This will install [tacowordpress](https://github.com/tacowordpress/tacowordpress) and all other composer-specified dependencies.

For information on how to use tacowordpress, check out the [repo](https://github.com/tacowordpress/tacowordpress). For complete documentation, see the [wiki](https://github.com/tacowordpress/tacowordpress/wiki).

## Running tasks inside the theme

For all frontend-related tasks, including: compiling, entrypoints, minification, see the theme's [README.md file](/src/README.md).
