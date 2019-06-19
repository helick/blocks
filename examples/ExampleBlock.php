<?php

use Carbon_Fields\Field;
use Helick\Blocks\Block;

final class ExampleBlock extends Block
{
    /**
     * The block's display name.
     *
     * @var string
     */
    protected $name = 'Example';

    /**
     * The block's description.
     *
     * @var string
     */
    protected $description = 'This is an example block';

    /**
     * The block's template.
     *
     * @var string|string[]
     */
    protected $template = 'partials/blocks/example.php';

    /**
     * Fields to be attached to the block.
     *
     * @return array
     */
    public function fields(): array
    {
        return [
            Field::make('text', 'heading', 'Heading'),
            Field::make('image', 'image', 'Image'),
            Field::make('rich_text', 'content', 'Content'),
        ];
    }
}
