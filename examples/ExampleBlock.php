<?php

use Carbon_Fields\Field;
use Helick\Blocks\Block;
use WP_Query;

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
            Field::make('association', 'associations', 'Associations')
                 ->set_types([
                     [
                         'type'      => 'post',
                         'post_type' => 'post',
                     ]
                 ])
        ];
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
        return [
            'associations' => $this->queryAssociations($fields['associations'])
        ];
    }

    /**
     * Query the associations.
     *
     * @param array $associations
     *
     * @return WP_Query
     */
    private function queryAssociations(array $associations): WP_Query
    {
        $associationIds = array_column($associations, 'id');
        $associationIds = array_map('intval', $associationIds);

        return new WP_Query([
            'no_found_rows' => true,
            'post__in'      => $associationIds,
            'orderby'       => 'post__in',
        ]);
    }
}
