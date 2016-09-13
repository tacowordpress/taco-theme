<img alt="Taco Theme Logo" src="https://raw.githubusercontent.com/tacowordpress/taco-theme/master/src/logo-taco-theme.gif" width="300">
---
# Taco Theme
> _Always start with a shell._ | A bare theme for WordPress that uses [TacoWordPress](https://github.com/tacowordpress/tacowordpress)

This theme can either be installed manually to an already existing WordPress installation, or it can be installed using the [taco-installer](https://github.com/tacowordpress/taco-installer), which will automate the setup for a brand new WordPress instance.

## Installing this theme manually to a WordPress instance

If you're not using the taco-installer, follow the instructions below

## Requirements
* [Composer](https://getcomposer.org/)

## Installing / Getting started

With a WordPress instance already setup, create a new directory in the theme and call it taco-theme. Download this taco-theme, and copy/paste all the contents from /src into the new WordPress taco-theme.

Next,

cd into the taco-theme's `/app/core` directory, and run `composer install`

This will install TacoWordpress and all other composer-specified dependencies. For information on how to use TacoWordpress, check out the [repo](https://github.com/tacowordpress/tacowordpress). For complete documentation, see the [wiki](https://github.com/tacowordpress/tacowordpress/wiki).

## Running tasks inside the theme

For all frontend instructions on running the theme compilers, entry points, sass compilation, javascript minification etc., see the theme's [README.md file](/src/README.md).
