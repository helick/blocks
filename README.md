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
