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

                <label for="collection_products">Add products with IDs (separate with ",")</label>
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
        <form method="get" id="sort-collections">
            <label for="category_filter">Category:</label>
            <?php
            // Dropdown for categories
            wp_dropdown_categories(array(
                'show_option_all' => 'All Categories',
                'taxonomy' => 'category',
                'name' => 'category_filter',
                'orderby' => 'name',
                'selected' => isset($_GET['category_filter']) ? $_GET['category_filter'] : 0,
                'hierarchical' => true,
                'show_count' => false,
                'hide_empty' => true,
            ));
            ?>

            <label for="sort_by">Sort By:</label>
            <select name="sort_by" id="sort_by" onchange="this.form.submit()">
                <option value="date" <?php selected($_GET['sort_by'], 'date'); ?>>Date Created</option>
            </select>

            <label for="order">Order:</label>
            <select name="order" id="order" onchange="this.form.submit()">
                <option value="ASC" <?php selected($_GET['order'], 'ASC'); ?>>Ascending</option>
                <option value="DESC" <?php selected($_GET['order'], 'DESC'); ?>>Descending</option>
            </select>

            <input type="submit" value="Sort">
        </form>

        <?php
        // Retrieve sorting options and category filter from URL parameters
        $sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'date';
        $order = isset($_GET['order']) ? $_GET['order'] : 'DESC';
        $category_filter = isset($_GET['category_filter']) ? (int) $_GET['category_filter'] : 0;

        $collection_args = array(
            'post_type' => 'product_collection',
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'order' => $order,
        );

        if ($sort_by === 'date') {
            $collection_args['orderby'] = 'date';
        } elseif ($sort_by === 'category') {
            $collection_args['orderby'] = 'title'; // Sort alphabetically if sorted by category
        }

        // Apply category filter if a category is selected
        if ($category_filter) {
            $collection_args['tax_query'] = array(
                array(
                    'taxonomy' => 'category',
                    'field' => 'term_id',
                    'terms' => $category_filter,
                ),
            );
        }

        $collections = new WP_Query($collection_args);

        if ($collections->have_posts()) {
            while ($collections->have_posts()) {
                $collections->the_post();

                // Get the categories associated with the collection
                $categories = get_the_terms(get_the_ID(), 'category');
                $category_names = $categories ? implode(', ', wp_list_pluck($categories, 'name')) : 'Uncategorized';
                ?>

                <div class="collection">
                    <h4><?php the_title(); ?></h4>

                    <!-- Display Date Created -->
                    <p><strong>Date Created:</strong> <?php echo get_the_date(); ?></p>

                    <!-- Display Categories -->
                    <p><strong>Categories:</strong> <?php echo esc_html($category_names); ?></p>

                    <?php if (has_post_thumbnail()) { ?>
                        <div class="collection-thumbnail">
                            <?php the_post_thumbnail('medium'); ?>
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

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Restore the scroll position
        if (sessionStorage.getItem("scrollPosition")) {
            window.scrollTo(0, sessionStorage.getItem("scrollPosition"));
            sessionStorage.removeItem("scrollPosition");
        }

        // Save scroll position before form submission
        document.getElementById("sort-collections").addEventListener("submit", function () {
            sessionStorage.setItem("scrollPosition", window.scrollY);
        });
    });
</script>

<?php get_footer(); ?>