<?php

namespace Helick\Blocks;

use Carbon_Fields\Block as CarbonFieldsBlock;
use Exception;
use Helick\Blocks\Contracts\Bootable;
use Helick\Blocks\Contracts\Composable;
use Helick\Blocks\Exceptions\BlockException;

abstract class Block implements Bootable, Composable
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
     * Control the block's ability of containing nested blocks.
     *
     * @var bool
     */
    protected $nested = false;

    /**
     * Control the position at which the nested blocks will render.
     *
     * The possible values are:
     * - above,
     * - below.
     *
     * @var string
     */
    protected $nestedPosition = '';

    /**
     * This property allows you to set a template of blocks which every new instance of your block will contain.
     *
     * @var array
     */
    protected $nestedTemplates = [];

    /**
     * This property allows you to lock the area that contains the nested blocks.
     *
     * The possible values are:
     * - all – prevents all operations.
     * - insert – prevents inserting or removing blocks, but allows moving existing ones.
     * - false – prevents locking from being applied to the nested blocks area even if a parent block contains locking.
     * - null – disables any locks applied to the area.
     *
     * @var string
     */
    protected $nestedLock = '';

    /**
     * This property allows you to restrict the type of blocks that can be inserted in the nested blocks area.
     *
     * @var null|string[]
     */
    protected $nestedBlocks = null;

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
     * Captures the output that is generated when a template is included.
     * This property is static to prevent object scope resolution.
     *
     * @param string $template
     * @param array  $data
     *
     * @return string
     *
     * @throws Exception
     */
    protected static function capture(string $template, array $data): string
    {
        extract($data, EXTR_SKIP);

        ob_start();

        try {
            include $template;
        } catch (Exception $e) {
            ob_end_clean();

            throw $e;
        }

        return ob_get_clean();
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
     * Render the block.
     *
     * [!!] Global variables with the same key name as local variables will be
     * overwritten by the local variable.
     *
     * @param array $fields
     * @param array $attributes
     * @param array $blocks
     *
     * @return void
     *
     * @throws Exception
     */
    public function render(array $fields, array $attributes, array $blocks): void
    {
        $globalVariables = compact('fields', 'attributes', 'blocks');
        $localVariables  = $this->with($fields);

        $data = array_merge($globalVariables, $localVariables);

        echo static::capture($this->template(), $data);
    }

    /**
     * Get the block's template.
     *
     * @return string
     */
    protected function template(): string
    {
        if (empty($this->template)) {
            throw BlockException::forEmptyTemplate();
        }

        $template = locate_template($this->template);
        if ($template === '') {
            throw BlockException::forNotFoundTemplate($this->template);
        }

        return $template;
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
