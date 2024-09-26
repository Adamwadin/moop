<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <?php wp_head(); ?>
</head>
<script>(function (w, d, s, l, i) {
        w[l] = w[l] || []; w[l].push({
            'gtm.start':
                new Date().getTime(), event: 'gtm.js'
        }); var f = d.getElementsByTagName(s)[0],
            j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : ''; j.async = true; j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl; f.parentNode.insertBefore(j, f);
    })(window, document, 'script', 'dataLayer', 'G-8F2M4FRSTG');</script>


<body <?php body_class(); ?>>

    <header class="site-header">
        <div class="container">
            <div class="site-title">
                <h1><a href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a></h1>

            </div>




            <div class="header-links">
                <a href="<?php echo get_post_type_archive_link('product_collection'); ?>">View All Collections</a>

                <a href="<?php echo wc_get_cart_url(); ?>" class="cart-link">
                    <i class="fas fa-shopping-cart"></i> Cart (<?php echo WC()->cart->get_cart_contents_count(); ?>)
                </a>


                <a href="<?php echo wc_get_checkout_url(); ?>" class="checkout-link">Checkout</a>

            </div>
        </div>
    </header>


    <?php wp_footer(); ?>
</body>

</html>