<?php

namespace Helick\Blocks\Traits;

trait CommonDeclaration
{
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
     * The block's category.
     *
     * @var string
     */
    protected $category = '';

    /**
     * The block's icon.
     *
     * @see https://developer.wordpress.org/resource/dashicons/
     *
     * @var string
     */
    protected $icon = '';

    /**
     * The block's keywords.
     *
     * @var string[]
     */
    protected $keywords = [];

    /**
     * Control the block's preview mode.
     *
     * @var bool
     */
    protected $preview = true;

    /**
     * This property allows you to restrict the block to be inserted to specific block types.
     *
     * @var null|string|string[]
     */
    protected $parent = null;

    /**
     * The block's template.
     *
     * @var string|string[]
     */
    protected $template = '';
}
