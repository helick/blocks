<?php

namespace Helick\Blocks\Traits;

trait NestedDeclaration
{
    /**
     * This property allows you to restrict the block to be inserted to specific block types.
     *
     * @var null|string|string[]
     */
    protected $parent = null;

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
}
