<?php

// Set error reporting level
if(WP_DEBUG && WP_DEBUG_LEVEL) error_reporting(WP_DEBUG_LEVEL);

require_once __DIR__.'/config/TacoConfigBase.php';
require_once __DIR__.'/config/TacoConfig.php';
require_once __DIR__.'/TacoThemeUtil.php';

// We have to init the config before loading classes, because they may need
// access to user-defined constants
TacoConfig::init();

require_once __DIR__.'/config/load-classes.php';

// We have to process the config after other classes are loaded, because some
// methods use the Util classes, which aren't loaded until now
TacoConfig::process();

// TODO: test autoloading all classes to see if that solves the issue of having
// to handle the config in two steps

require_once __DIR__.'/config/init.php';
