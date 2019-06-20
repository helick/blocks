<?php

namespace Helick\Blocks;

use Helick\Blocks\Contracts\Bootable;
use Helick\Blocks\Contracts\Composable;

/**
 * Get the list of registered blocks.
 *
 * @param array $blocks
 *
 * @return array
 */
function blocks(array $blocks = []): array
{
    /**
     * Control the list of registered blocks.
     *
     * @param array $blocks
     */
    $blocks = apply_filters('helick_blocks', $blocks);

    $blocks = array_filter((array)$blocks, function (string $class) {
        return $class instanceof Composable;
    });

    $blocks = array_unique($blocks);

    return $blocks;
}

/**
 * Bootstrap the given blocks.
 *
 * @param array $blocks
 *
 * @return void
 */
function bootstrap(array $blocks): void
{
    $blocks = array_filter($blocks, function (string $class) {
        return $class instanceof Bootable;
    });

    array_walk($blocks, static function (Bootable $block) {
        $block::boot();
    });
}
