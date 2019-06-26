# Helick Block

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Software License][ico-license]](LICENSE.md)
[![Quality Score][ico-code-quality]][link-code-quality]

The package assists you in easily creating Gutenberg blocks with Carbon Fields.

## Requirements

Make sure all dependencies have been installed before moving on:

* [PHP](http://php.net/manual/en/install.php) >= 7.1
* [Composer](https://getcomposer.org/download/)
* [Carbon Fields](https://docs.carbonfields.net/#/quickstart) >= 3.0

## Install

Install via Composer:

``` bash
$ composer require helick/blocks
```

## Usage

Within your theme declare your block, attach its fields, and provide data for your template:

``` php
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

```

Create your block template:

``` php
<div class="block">
    <div class="block__heading">
        <h1><?= esc_html($fields['heading']) ?></h1>
    </div>
    <div class="block__image">
        <?= wp_get_attachment_image($fields['image'], 'full') ?>
    </div>
    <div class="block__content">
        <?= apply_filters('the_content', $fields['content']) ?>
    </div>
    <?php if ($associations->have_posts()) : ?>
        <div class="block__associations">
            <ul class="block__associations-list">
                <?php while ($associations->have_posts()) : $associations->the_post(); ?>
                    <li class="block__associations-item">
                        <a class="block__associations-link" href="<?= esc_url(get_the_permalink()) ?>">
                            <?= esc_html(get_the_title()) ?>
                        </a>
                    </li>
                <?php endwhile; ?>
            </ul>
        </div>
        <?php wp_reset_postdata(); ?>
    <?php endif; ?>
</div>

```

Finally, register your block in theme's `functions.php`:

``` php
ExampleBlock::boot();
```

## Caching

The easiest and probably the best method is to cache the **complete HTML output**, and PHP's output buffering functions will help us implement that without moving too much code around:

``` php
use Carbon_Fields\Field;
use Helick\Blocks\Block;
use Exception;

final class ExampleBlock extends Block
{
    // Your block declaration goes in here ...

    /**
     * Render the block.
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
        // Compose the render arguments
        $args = compact('fields', 'attributes', 'blocks');

        // Generate cache key based on the given arguments
        $cacheKey   = sprintf('example_block_%s', hash('md5', wp_json_encode($args)));
        $cacheGroup = 'blocks';

        // Check whether we have the block's cached output
        $output = wp_cache_get($cacheKey, $cacheGroup);

        // If nothing is found, catch the block render output
        if (false === $output) {
            ob_start();

            try {
                parent::render($fields, $attributes, $blocks);
            } catch (Exception $e) {
                // In case something goes wrong we clear the output buffer
                ob_end_clean();

                // Re-throw an exception so we don't cache the actual error output
                throw $e;
            }

            $output = ob_get_clean();

            // Cache the block's output for 5 minutes (300 secs)
            wp_cache_set($cacheKey, $output, $cacheGroup, 5 * MINUTE_IN_SECONDS);
        }

        echo $output;
        echo "<!-- Cache Key: {$cacheKey} -->";
    }
}
```

With this way we're only storing the actual output in our cache, no posts, no metadata, no terms. Just the HTML.

You can also inspect your cache by using [WP CLI](https://wp-cli.org/):

``` bash
# Get the block's output from the object cache
$ wp cache get example_block_098f6bcd4621d373cade4e832627b4f6 blocks
...your block's output...

# Remove the block's output from the object cache
$ wp cache delete example_block_098f6bcd4621d373cade4e832627b4f6 blocks
Success: Object deleted.
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CODE_OF_CONDUCT](CODE_OF_CONDUCT.md) for details.

## Security

If you discover any security related issues, please email evgenii@helick.io instead of using the issue tracker.

## Credits

- [Evgenii Nasyrov][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/helick/blocks.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/helick/blocks.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/helick/blocks.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/helick/blocks
[link-code-quality]: https://scrutinizer-ci.com/g/helick/blocks
[link-downloads]: https://packagist.org/packages/helick/blocks
[link-author]: https://github.com/nasyrov
[link-contributors]: ../../contributors
