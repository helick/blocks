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

// Let's compose the registered blocks
add_action('carbon_fields_register_fields', function () {
    /**
     * Control the list of registered blocks.
     *
     * @param array $blocks
     */
    $blocks = apply_filters('helick_blocks', []);

    $blocks = array_filter((array)$blocks, function (string $class) {
        return $class instanceof \Helick\Blocks\Contracts\Composable;
    });

    $blocks = array_unique($blocks);

    array_walk($blocks, static function (string $class) {
        (new $class)->compose();
    });
});

