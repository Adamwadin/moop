<?php get_header(); ?>

<main>
    <h2>Welcome to Our Store</h2>
    <?php get_sidebar(); ?>
    <?php if (is_user_logged_in()): ?>
        <div class="create-collection">
            <h3>Create New Collection</h3>
            <form method="post">
                <label for="collection_title">Collection Title</label>
                <input type="text" id="collection_title" name="collection_title" required>

                <label for="collection_products">add products with IDs (seperate with ",")</label>
                <input type="text" id="collection_products" name="collection_products" required>

                <input type="submit" name="submit_collection" value="Create Collection" class="button">



            </form>
        </div>

        <?php
        if (isset($_POST['submit_collection'])) {
            $title = sanitize_text_field($_POST['collection_title']);
            $product_ids = explode(',', sanitize_text_field($_POST['collection_products']));

            $collection_id = wp_insert_post(array(
                'post_title' => $title,
                'post_type' => 'product_collection',
                'post_status' => 'publish',
                'post_author' => get_current_user_id(),
            ));

            if ($collection_id) {
                update_post_meta($collection_id, '_collection_products', $product_ids);
                echo '<p>Collection created successfully!</p>';
            } else {
                echo '<p>Failed to create collection. Please try again.</p>';
            }
        }
        ?>
    <?php endif; ?>


    <div class="products">
        <h3>Our Products</h3>
        <?php
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        );
        $loop = new WP_Query($args);

        if ($loop->have_posts()) {
            while ($loop->have_posts()) {
                $loop->the_post();
                global $product;
                ?>

                <div class="product">
                    <h4><?php the_title(); ?></h4>
                    <?php if (has_post_thumbnail()) { ?>
                        <div class="product-image">
                            <?php echo get_the_post_thumbnail(get_the_ID(), 'medium'); ?>
                        </div>
                    <?php } else { ?>
                        <div class="product-image">
                            <img src="path/to/placeholder-image.jpg" alt="Temporary Image" />
                        </div>

                    <?php } ?>
                    <p><?php echo $product->get_price_html(); ?></p>
                    <a href="<?php the_permalink(); ?>" class="button">View Product</a>
                    <a>ID:</a><?php echo get_the_ID(); ?>
                    <a href="<?php echo esc_url($product->add_to_cart_url()); ?>" class="button add_to_cart_button"
                        data-product_id="<?php echo get_the_ID(); ?>">
                        <?php echo esc_html($product->add_to_cart_text()); ?>
                    </a>
                </div>

                <?php
            }
            wp_reset_postdata();
        } else {
            echo '<p>No products found.</p>';
        }
        ?>
    </div>

    <div class="collections">
        <h3>Our Collections</h3>
        <?php
        $collection_args = array(
            'post_type' => 'product_collection',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        );
        $collections = new WP_Query($collection_args);

        if ($collections->have_posts()) {
            while ($collections->have_posts()) {
                $collections->the_post();
                ?>
                <div class="collection">
                    <h4><?php the_title(); ?></h4>
                    <?php if (has_post_thumbnail()) { ?>
                        <div class="collection-thumbnail">
                            <?php the_post_thumbnail('medium'); // Adjust the size as needed ?>
                        </div>
                    <?php } ?>
                    <a href="<?php the_permalink(); ?>" class="button">View Collection</a>
                </div>
                <?php
            }
            wp_reset_postdata();
        } else {
            echo '<p>No collections found.</p>';
        }
        ?>
    </div>


</main>


<?php get_footer(); ?>