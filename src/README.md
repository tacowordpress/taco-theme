# Taco Theme
> Instructions for running frontend compilers, et al, in this theme.

## Backend Requirements

Installing Taco to the theme can be done in 1 of 2 ways,

1. using the [taco-installer](https://github.com/tacowordpress/taco-installer), or
2. manually install the taco theme to a WordPress instance by reviewing this [README.md](https://github.com/tacowordpress/taco-theme) one level above.

## Frontend Requirements
* [Node.js](https://nodejs.org/en/)
* [Webpack](https://webpack.github.io/docs/installation.html)

## Getting started

cd into the theme's root directory, and run:

`npm install`

This will add node modules required for the theme to run tasks, particularly for webpack. To begin development, run:

`webpack -d --watch`

This will create development builds, watch your JavaScript and SASS files.

## Webpack Configuration

Webpack configuration is defined on the `webpack.config.js`

### Development Tasks

Running `webpack -d --watch` will look for changes across files and compile them.

### Production Tasks

Running `webpack -p` will run minification on files.

### Webpack src and dist

Source files are located in `_/src` directory of the theme. Compiled files are added by webpack to the `_/dist`, when they are referenced.

### Webpack JS entrypoint

All Javascript files in the top level `_/src/js` folder are built and output to the `_/dist` folder.  Any Javascript meant to be included by these top level files should go under subdirectories in the `_/src/js` folder.  The top level Javascript files are responsible for importing any SASS files that need to be built and output (see `src/js/main.js` for an example).

### Webpack SASS entrypoint
SASS files similarly live in the `_/src/scss` directory.  These files are not auto compiled until they're included by a Javascript file, so it's not entirely necessary to enforce a naming convention for these files, but in general, included files should begin with an underscore and top level files should not.

### Webpack CSS entrypoint
At this time, CSS also outputs to the `_/dist` directory and does not output by the Javascript itself, so you do need to explicitly include them in your HTML.


/* @Ian, please advise for everything below this line, as we've discussed this is out of date for now */
## Optional git hooks for deploying `/dist` files:
Run `init` from the taco theme root directory to setup git hooks.

## Deployment Instructions
Until a site is live, development can happen on the `master` branch which can be auto-deployed to both the staging and production server.  Once it's launched however, development should be switched to the `develop` branch which is auto-deployed to the staging server.  The production server should run off the `master` branch and have deployment set to manual.



