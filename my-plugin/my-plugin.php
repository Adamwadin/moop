<?php
/**
 * Plugin Name: kollektion plugin, hoppas den funkar
 * Description: A custom WooCommerce plugin to allow users to create and view product collections.
 * Version: 1.0
 * Author: Beppe wadin
 */

if (!defined('ABSPATH')) {
    exit;
}
function create_collection_taxonomy()
{
    register_taxonomy(
        'collection_category',
        'product_collection',
        array(
            'label' => __('Kategorier'),
            'rewrite' => array('slug' => 'collection-category'),
            'hierarchical' => true,
        )
    );
}
add_action('init', 'create_collection_taxonomy');



require_once plugin_dir_path(__FILE__) . 'includes/collections.php';
