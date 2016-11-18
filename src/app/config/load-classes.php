<?php

// autoload vendor files (composer backend)
require_once realpath(__DIR__.'/../core/vendor/autoload.php');

// load frontend files from composer dir
require_once __DIR__.'/../core/CustomLoader.php';

// let's autoload some files
// load the psr-4 autoloader class file
require_once __DIR__.'/../core/Psr4AutoloaderClass.php';
$loader = new Psr4AutoloaderClass;
$loader->register();

// assign namespaces and their corresponding autoload paths here
$loader->addNamespace('\AppLibrary\\', __DIR__.'/../lib/AppLibrary/src');


// Traits
require_once __DIR__.'/../traits/Taquito.php';

// Taxonomies
require_once __DIR__.'/../terms/Category.php';

// Post types
require_once __DIR__.'/../posts/AppOption.php';
require_once __DIR__.'/../posts/Post.php';
require_once __DIR__.'/../posts/Page.php';

// Shortcuts
class Arr extends \Taco\Util\Arr {}
class Collection extends \Taco\Util\Collection {}
class Color extends \Taco\Util\Color {}
class Html extends \Taco\Util\Html {}
class Num extends \Taco\Util\Num {}
class Obj extends \Taco\Util\Obj {}
class Str extends \Taco\Util\Str {}
class Theme extends \Taco\Util\Theme {}
class View extends \Taco\Util\View {}
class States extends \AppLibrary\States {}
