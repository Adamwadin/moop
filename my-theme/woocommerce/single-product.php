<?php
defined('ABSPATH') || exit;

get_header('shop'); ?>

<div class="custom-single-product-container">
    <?php
    do_action('woocommerce_before_main_content');
    ?>

    <div class="custom-product-main">
        <?php while (have_posts()): ?>
            <?php the_post(); ?>

            <?php wc_get_template_part('content', 'single-product'); ?>

        <?php endwhile; ?>
    </div>


</div>

<?php get_footer('shop'); ?>