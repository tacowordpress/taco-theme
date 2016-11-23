<?php

// This automatically pulls from webpack_hash.  This will bust the cache whenever
// the any js or css asset changes
define('THEME_VERSION', file_get_contents(__DIR__.'/../_/dist/webpack_hash'));
define('THEME_SUFFIX', sprintf('?ver=%s', THEME_VERSION));
define('USER_SUPER_ADMIN', 'vermilion_admin'); // vermilion_admin
