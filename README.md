

<img alt="Taco Theme Logo" src="https://raw.githubusercontent.com/tacowordpress/taco-theme/master/src/logo-taco-theme.gif" width="300">
---
_Always start with a shell._ | A bare theme for WordPress that uses TacoWordPress

### Setup
This theme can be installed through the taco-installer or by moving the contents of the "src" directory out into a folder that's in "wp-content/themes".

**Optional Git hooks for deploying distribution files**

Run `init` from the Taco theme directory to set up your Git hooks. 

### Webpack
Run `webpack -d --watch` from the theme directory to create development builds and watch your Javascript and SASS files.

Run `webpack -p` from the theme directory to do a production build.

##### Sources
Source files are located in the `src` directory in the Taco theme directory, and compiled files and sourcemaps are located in the `dist` folder.

All Javascript files in the top level `src/js` folder are built and output to the `dist` folder.  Any Javascript meant to be included by these top level files should go under subdirectories in the `src/js` folder.  The top level Javascript files are responsible for importing any SASS files that need to be built and output (see `src/js/main.js` for an example).

SASS files similarly live in the `src/scss` directory.  These files are not auto compiled until they're included by a Javascript file, so it's not entirely necessary to enforce a naming convention for these files, but in general, included files should begin with an underscore and top level files should not.

At this time, CSS is also output to the `dist` directory and not output by the Javascript itself, so you do need to explicitly include them in your HTML.

### Deploying
Until a site is live, development can happen on the `master` branch which can be auto-deployed to both the staging and production server.  Once it's launched however, development should be switched to the `develop` branch which is auto-deployed to the staging server.  The production server should run off the `master` branch and have deployment set to manual.
