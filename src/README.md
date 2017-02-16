# Taco Theme
> Instructions for running frontend compilers, et al.

## Backend Requirements

Installing Taco to the theme can be done in 1 of 2 ways,

1. using the [taco-installer](https://github.com/tacowordpress/taco-installer), or
2. manually install the Taco Theme to a WordPress instance,
  * see the [README.md](https://github.com/tacowordpress/taco-theme) one level above.

## Frontend Requirements
* [Node.js](https://nodejs.org/en/)
* [Webpack](https://webpack.github.io/docs/installation.html)

## Getting started

cd into the theme's root directory, and run:

`npm install`

This will add node modules required for the theme to run tasks, particularly for webpack. To begin development, run:

`npm start`

This will create development builds, watch your JavaScript and SASS files.

## Webpack Configuration

Webpack configuration is defined on the `webpack.config.js`

Source files are located in `_/src` directory of the theme. Compiled files are dynamically added by webpack to the `_/dist`, when they are referenced via entrypoints.

### Webpack JS entrypoint

All Javascript files in the top level `_/src/js` folder are built and output to the `_/dist` folder.  Any Javascript meant to be included by these top level files should go under subdirectories in the `_/src/js` folder.  The top level Javascript files are responsible for importing any SASS files that need to be built and output (see `_/src/js/main.js` for an example).

### Webpack SASS entrypoint
SASS files similarly live in the `_/src/scss` directory.  These files are not auto compiled until they're included by a Javascript file, so it's not entirely necessary to enforce a naming convention for these files, but in general, included files should begin with an underscore and top level files should not.

### Webpack CSS entrypoint
At this time, CSS also outputs to the `_/dist` directory and does not output by the Javascript itself, so you do need to explicitly include them in your HTML.

## Deployment Instructions
Until a site is live, development can happen on the `master` branch which can be auto-deployed to both the staging and production server.  Once it's launched however, development should be switched to the `develop` branch which is auto-deployed to the staging server.  The production server should run off the `master` branch and have deployment set to manual.



