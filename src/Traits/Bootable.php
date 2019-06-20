<?php

namespace Helick\Blocks\Traits;

trait Bootable
{
    /**
     * Boot the block.
     *
     * @return void
     */
    public static function boot(): void
    {
        (new static)->compose();
    }
}
