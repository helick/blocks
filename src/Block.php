<?php

namespace Helick\Blocks;

abstract class Block implements Contracts\Bootable, Contracts\Composable
{
    use Traits\NestedDeclaration,
        Traits\Bootable,
        Traits\Composable,
        Traits\Renderable;

    /**
     * The block's display name.
     *
     * @var string
     */
    protected $name = '';

    /**
     * The block's description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * The block's icon.
     *
     * @var string
     */
    protected $icon = '';

    /**
     * The block's category.
     *
     * @var string
     */
    protected $category = '';

    /**
     * The block's keywords.
     *
     * @var string[]
     */
    protected $keywords = [];

    /**
     * The block's preview mode.
     *
     * @var bool
     */
    protected $preview = true;

    /**
     * The block's template(s).
     *
     * @var string|string[]
     */
    protected $template = '';

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
