<?php
/*
Plugin Name:    Helick Blocks
Author:         Evgenii Nasyrov
Author URI:     https://helick.io/
*/

// Require Composer autoloader if installed on it's own
if (file_exists($composer = __DIR__ . '/vendor/autoload.php')) {
    require_once $composer;
}
