# Helick Block

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]
[![Software License][ico-license]](LICENSE.md)
[![Quality Score][ico-code-quality]][link-code-quality]

The plugin assists you in easily creating Gutenberg blocks with Carbon Fields.

## Requirements

Make sure all dependencies have been installed before moving on:

* [PHP](http://php.net/manual/en/install.php) >= 7.1
* [Composer](https://getcomposer.org/download/)
* [Carbon Fields](https://docs.carbonfields.net/#/quickstart) >= 3.0

## Install

Via Composer:

``` bash
$ composer require helick/blocks
```

## Usage

Within your theme declare your block, attach its fields, and provide data for your template:

``` php
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
```

Create your block template:

``` php
<div class="block">
    <div class="block__heading">
        <h1><?php echo esc_html($fields['heading']); ?></h1>
    </div>
    <div class="block__image">
        <?php echo wp_get_attachment_image($fields['image'], 'full'); ?>
    </div>
    <div class="block__content">
        <?php echo apply_filters('the_content', $fields['content']); ?>
    </div>
</div>
```

Finally, register your block in theme's `functions.php`:

``` php
add_filter('helick_blocks', function (array $blocks) {
    $blocks[] = ExampleBlock::class;

    return $blocks;
});
```

## Caching

The easiest and probably the best method is to cache the **complete HTML output**, and PHP's output buffering functions will help us implement that without moving too much code around:

``` php
use Carbon_Fields\Field;
use Helick\Blocks\Block;

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

            // If someting goes wrong during the render,
            // we clear the output and re-throw an exception
            // so we don't cache the actual error output.
            try {
                parent::render($fields, $attributes, $blocks);
            } catch (Exception $e) {
                ob_end_clean();

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
