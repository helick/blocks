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
        add_action('carbon_fields_register_fields', function () {
            (new static)->compose();
        });
    }
}
