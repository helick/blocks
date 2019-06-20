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
