<?php

namespace Helick\Blocks\Traits;

use Carbon_Fields\Block as CarbonFieldsBlock;
use Helick\Blocks\Exceptions\BlockException;

trait Composable
{
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
                         ->set_icon($this->icon)
                         ->set_category($this->category)
                         ->set_keywords($this->keywords)
                         ->set_preview_mode($this->preview)
                         ->set_parent($this->parent)
                         ->set_inner_blocks($this->nested)
                         ->set_inner_blocks_position($this->nestedPosition)
                         ->set_inner_blocks_template($this->nestedTemplates)
                         ->set_inner_blocks_template_lock($this->nestedLock)
                         ->set_allowed_inner_blocks($this->nestedBlocks)
                         ->set_render_callback([$this, 'render']);
    }
}
