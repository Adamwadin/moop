<?php
if (!defined('ABSPATH')) {
    exit;
}

function register_collection_category_taxonomy()
{
    register_taxonomy(
        'collection_category',
        'product_collection',
        array(
            'label' => __('Collection Categories'),
            'rewrite' => array('slug' => 'collection-category'),
            'hierarchical' => true,
        )
    );
}
add_action('init', 'register_collection_category_taxonomy');

function create_product_collection_cpt()
{
    $labels = array(
        'name' => 'Collections',
        'singular_name' => 'Collection',
        'menu_name' => 'Collections',
        'name_admin_bar' => 'Collection',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Collection',
        'new_item' => 'New Collection',
        'edit_item' => 'Edit Collection',
        'view_item' => 'View Collection',
        'all_items' => 'All Collections',
        'search_items' => 'Search Collections',
        'not_found' => 'No collections found.',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'supports' => array('title', 'editor', 'thumbnail'),
        'show_in_rest' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-cart',
        'rewrite' => array('slug' => 'collections'),
        'taxonomies' => array('category'),
    );

    register_post_type('product_collection', $args);

    register_taxonomy_for_object_type('category', 'product_collection');
}
add_action('init', 'create_product_collection_cpt');

function add_collection_meta_box()
{
    add_meta_box('product_collection_meta', 'Select Products', 'display_collection_meta_box', 'product_collection', 'normal', 'high');
}
add_action('add_meta_boxes', 'add_collection_meta_box');

function display_collection_meta_box($post)
{
    $products = get_posts(array(
        'post_type' => 'product',
        'posts_per_page' => -1,
    ));

    $selected_products = get_post_meta($post->ID, '_collection_products', true);

    echo '<div>';
    foreach ($products as $product) {
        $checked = in_array($product->ID, (array) $selected_products) ? 'checked' : '';
        echo '<label><input type="checkbox" name="collection_products[]" value="' . esc_attr($product->ID) . '" ' . $checked . '> ' . esc_html($product->post_title) . '</label><br>';
    }
    echo '</div>';
}


function save_collection_products($post_id)
{
    if (isset($_POST['collection_products'])) {
        update_post_meta($post_id, '_collection_products', $_POST['collection_products']);
    } else {
        delete_post_meta($post_id, '_collection_products');
    }
}
add_action('save_post', 'save_collection_products');

function add_collection_to_cart()
{
    if (isset($_POST['collection_id']) && isset($_POST['add_collection_to_cart_nonce'])) {
        if (!wp_verify_nonce($_POST['add_collection_to_cart_nonce'], 'add_collection_to_cart')) {
            return;
        }

        $collection_id = intval($_POST['collection_id']);
        $products = get_post_meta($collection_id, '_collection_products', true);

        if (!empty($products)) {
            foreach ($products as $product_id) {
                WC()->cart->add_to_cart($product_id);
            }
            wp_redirect(wc_get_cart_url());
            exit;
        }
    }
}
add_action('init', 'add_collection_to_cart');
