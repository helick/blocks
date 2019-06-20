<?php

namespace Helick\Blocks;

abstract class Block implements Contracts\Bootable, Contracts\Composable
{
    use Traits\CommonDeclaration,
        Traits\NestedDeclaration,
        Traits\Bootable,
        Traits\Composable,
        Traits\Renderable;

    /**
     * Fields to be attached to the block.
     *
     * @return array
     */
    public function fields(): array
    {
        return [];
    }

    /**
     * Data to be passed to the rendered block.
     *
     * @param array $fields
     *
     * @return array
     */
    public function with(array $fields): array
    {
        return [];
    }
}
