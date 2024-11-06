<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['assign_category']) && isset($_POST['collection_id'])) {
        $collection_id = intval($_POST['collection_id']);
        $category_id = isset($_POST['collection_category']) ? intval($_POST['collection_category']) : 0;

        if ($category_id) {
            wp_set_object_terms($collection_id, $category_id, 'category');
        }
    }

    if (isset($_POST['add_collection_to_cart']) && isset($_POST['collection_id'])) {
        $products = get_post_meta($_POST['collection_id'], '_collection_products', true);
        if (!empty($products)) {
            foreach ($products as $product_id) {
                WC()->cart->add_to_cart($product_id);
            }
        }
    }
}
?>

<?php get_header(); ?>

<main>
    <h2><?php the_title(); ?></h2>

    <div class="collection-details">
        <?php if (has_post_thumbnail()) { ?>
            <div class="collection-thumbnail">
                <?php the_post_thumbnail('large'); ?>
            </div>
        <?php } ?>
        <p>Created on: <?php echo get_the_date(); ?></p>
        <div class="collection-description">
            <?php the_content(); ?>
        </div>

        <?php

        $products = get_post_meta(get_the_ID(), '_collection_products', true);

        if (!empty($products)) {
            ?>

            <h3>Products in this Collection</h3>
            <div class="current-category">
                <?php
                $categories = get_the_terms(get_the_ID(), 'category');
                if ($categories && !is_wp_error($categories)) {
                    echo '<p>Categories: ';
                    $category_links = array();
                    foreach ($categories as $category) {
                        $category_links[] = '<a href="' . esc_url(get_term_link($category)) . '">' . esc_html($category->name) . '</a>';
                    }
                    echo implode(', ', $category_links);
                    echo '</p>';
                } else {
                    echo '<p>No categories assigned.</p>';
                }


                ?>
            </div>
            <form method="post" action="">
                <label for="collection_category">Assign Category:</label>
                <select name="collection_category" id="collection_category">
                    <option value="">Select a category</option>
                    <?php
                    $categories = get_terms(array(
                        'taxonomy' => 'category',
                        'hide_empty' => false,
                    ));

                    foreach ($categories as $category) {
                        echo '<option value="' . $category->term_id . '">' . $category->name . '</option>';
                    }
                    ?>
                </select>

                <input type="hidden" name="collection_id" value="<?php echo get_the_ID(); ?>">
                <button type="submit" name="assign_category" class="button">Assign Category</button>
            </form>
            <ul class="collection-products">
                <?php foreach ($products as $product_id) {
                    $product = wc_get_product($product_id);
                    if ($product) {
                        ?>
                        <li>
                            <a href="<?php echo get_permalink($product_id); ?>">
                                <?php echo $product->get_image('thumbnail'); ?>
                                <p><?php echo $product->get_name(); ?></p>
                            </a>
                            <p>Price: <?php echo $product->get_price_html(); ?></p>
                        </li>
                        <?php
                    }
                } ?>
            </ul>

            <form method="post" action="">
                <input type="hidden" name="collection_id" value="<?php echo get_the_ID(); ?>">
                <?php wp_nonce_field('add_collection_to_cart', 'add_collection_to_cart_nonce'); ?>
                <button type="submit" name="add_collection_to_cart" class="button">Add All to Cart</button>
            </form>

            <?php
        } else {
            echo '<p>No products in this collection.</p>';
        }
        ?>
    </div>
</main>

<?php get_sidebar(); ?>
<?php get_footer(); ?>