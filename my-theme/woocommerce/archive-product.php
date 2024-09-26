<?php
defined('ABSPATH') || exit;

get_header(); ?>

<main id="main-content" class="site-main">

    <header class="woocommerce-products-header">
        <?php if (apply_filters('woocommerce_show_page_title', true)): ?>
            <h1 class="woocommerce-products-header__title page-title"><?php woocommerce_page_title(); ?></h1>
        <?php endif; ?>

        <?php do_action('woocommerce_archive_description'); ?>
    </header>



</main>

<?php get_footer(); ?>