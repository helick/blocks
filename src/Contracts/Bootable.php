<?php

namespace Helick\Blocks\Contracts;

interface Bootable
{
    /**
     * Boot the block.
     *
     * @return void
     */
    public static function boot(): void;
}
