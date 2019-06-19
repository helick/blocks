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
