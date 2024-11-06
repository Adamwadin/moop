<?php
function my_custom_theme_setup()
{
    add_theme_support('woocommerce');
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'my-custom-theme'),
    ));
}
add_action('after_setup_theme', 'my_theme_woocommerce_support');
function my_theme_woocommerce_support()
{
    add_theme_support('woocommerce');
}

add_action('wp_body_open', 'add_gtm_noscript');
function add_gtm_noscript()
{
    ?>
    <noscript>
        <iframe src="https://www.googletagmanager.com/ns.html?id=G-8F2M4FRSTG" height="0" width="0"
            style="display:none;visibility:hidden"></iframe>
    </noscript>
    <?php
}

add_action('pre_get_posts', 'sort_collections_by_params');
function sort_collections_by_params($query)
{
    if (!is_admin() && $query->is_main_query() && is_post_type_archive('product_collection')) {
        if ($category_filter = get_query_var('category_filter')) {
            $query->set('tax_query', array(
                array(
                    'taxonomy' => 'category',
                    'field' => 'term_id',
                    'terms' => $category_filter,
                    'operator' => 'IN',
                ),
            ));
        }

        if ($sort_by = get_query_var('sort_by')) {
            $query->set('orderby', $sort_by);
            $order = get_query_var('order') ? get_query_var('order') : 'ASC';
            $query->set('order', $order);
        }
    }
}
function add_custom_query_vars($vars)
{
    $vars[] = 'category_filter';
    $vars[] = 'sort_by';
    $vars[] = 'order';
    return $vars;
}
add_filter('query_vars', 'add_custom_query_vars');


function my_custom_theme_scripts()
{
    wp_enqueue_style('style', get_stylesheet_uri());
}
function my_theme_enqueue_styles()
{
    wp_enqueue_style('my-theme-style', get_stylesheet_directory_uri() . '/style.css');
}
function my_child_theme_enqueue_styles()
{
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');

    wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style'));
}




function custom_cart_url()
{
    return get_permalink(38);
}



add_filter('woocommerce_get_cart_url', 'custom_cart_url');



add_action('after_setup_theme', 'my_custom_theme_setup');
add_action('wp_enqueue_scripts', 'my_theme_enqueue_styles');
add_action('wp_enqueue_scripts', 'my_child_theme_enqueue_styles');


?>