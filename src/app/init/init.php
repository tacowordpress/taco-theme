<?php

// Set error reporting level
if(WP_DEBUG && WP_DEBUG_LEVEL) error_reporting(WP_DEBUG_LEVEL);

require_once __DIR__.'/ConfigBase.php';
require_once __DIR__.'/../config/Config.php';

use \Taco\Config as Config;

// We have to init the config before loading classes, because they may need
// access to user-defined constants
Config::init();

require_once __DIR__.'/autoload-classes.php';

// We have to process the config after other classes are loaded, because some
// methods use the Util classes, which aren't loaded until now
Config::process();

// TODO: test autoloading all classes to see if that solves the issue of having
// to handle the config in two steps

\TacoApp\CustomLoader::init();
\Taco\Loader::init();
\JasandPereza\Loader::init();
\AddBySearch\Loader::init();
