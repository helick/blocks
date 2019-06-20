<?php

namespace Helick\Blocks;

use Carbon_Fields\Block as CarbonFieldsBlock;
use Helick\Blocks\Contracts\Bootable;
use Helick\Blocks\Contracts\Composable;
use Helick\Blocks\Exceptions\BlockException;

abstract class Block implements Bootable, Composable
{
    use Traits\CommonDeclaration,
        Traits\NestedDeclaration,
        Traits\Renderable;

    /**
     * Boot the block.
     *
     * @return void
     */
    public static function boot(): void
    {
        (new static)->compose();
    }

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

    /**
     * Compose the block.
     *
     * @return void
     */
    public function compose(): void
    {
        if (empty($this->name)) {
            throw BlockException::forEmptyName();
        }

        CarbonFieldsBlock::make($this->name)
                         ->add_fields($this->fields())
                         ->set_description($this->description)
                         ->set_category($this->category)
                         ->set_icon($this->icon)
                         ->set_keywords($this->keywords)
                         ->set_inner_blocks($this->nested)
                         ->set_inner_blocks_position($this->nestedPosition)
                         ->set_inner_blocks_template($this->nestedTemplates)
                         ->set_inner_blocks_template_lock($this->nestedLock)
                         ->set_allowed_inner_blocks($this->nestedBlocks)
                         ->set_parent($this->parent)
                         ->set_render_callback([$this, 'render']);
    }

    /**
     * Get the block's path.
     *
     * @return string
     */
    protected function path(): string
    {
        return dirname(__FILE__);
    }

    /**
     * Get the block's uri.
     *
     * @param string $uri
     *
     * @return string
     */
    protected function uri(string $uri = ''): string
    {
        return str_replace(get_theme_file_path(), get_theme_file_uri(), home_url($uri));
    }
}
