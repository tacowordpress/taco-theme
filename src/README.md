# Taco Theme
> Instructions for running frontend compilers, et al, in this theme.

## Frontend Requirements
* [Node.js](https://nodejs.org/en/)
* [Webpack](https://webpack.github.io/docs/installation.html)

This theme uses TacoWordpress for backend models and functionality, and it uses Webpack as a frontend task runner.

## Getting started

cd into the theme's root directory, and run:

`npm install`

This will add node modules required for the theme to run tasks, particularly for webpack. To begin development, run:

`webpack -d --watch`

This will create development builds, watch your JavaScript and SASS files.

## Webpack Configuration

Webpack currently has two commands to run, `webpack -d --watch` for usual development tasks, and `webpack -p` to run production tasks. The major difference is that when deploying to a production server, first run `webpack -p` to regenerate the minified files. All other configurations are on the `webpack.config.js`

### Webpack src and dist

Source files are located in `/src` in the `_` directory of the theme. Compiled files are added by webpack to the `/dist`, when they are referenced.

### Webpack JS entrypoint

All Javascript files in the top level `src/js` folder are built and output to the `dist` folder.  Any Javascript meant to be included by these top level files should go under subdirectories in the `src/js` folder.  The top level Javascript files are responsible for importing any SASS files that need to be built and output (see `src/js/main.js` for an example).

### Webpack SASS entrypoint
SASS files similarly live in the `src/scss` directory.  These files are not auto compiled until they're included by a Javascript file, so it's not entirely necessary to enforce a naming convention for these files, but in general, included files should begin with an underscore and top level files should not.

### Webpack CSS entrypoint
At this time, CSS also outputs to the `dist` directory and does not output by the Javascript itself, so you do need to explicitly include them in your HTML.


## Optional git hooks for deploying `/dist` files:
Run `init` from the taco theme root directory to setup git hooks.

## Deployment Instructions
Until a site is live, development can happen on the `master` branch which can be auto-deployed to both the staging and production server.  Once it's launched however, development should be switched to the `develop` branch which is auto-deployed to the staging server.  The production server should run off the `master` branch and have deployment set to manual.



