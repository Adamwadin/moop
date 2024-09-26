<?php get_header(); ?>

<main>
    <h2>Our Collections</h2>

    <div class="filter">
        <form method="GET" action="<?php echo esc_url(home_url('/')); ?>">
            <?php
            $categories = get_categories(array('taxonomy' => 'category'));
            ?>
            <select name="category" onchange="this.form.submit()">
                <option value="">Select a category</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo esc_attr($category->term_id); ?>" <?php selected($category->term_id, get_query_var('category')); ?>>
                        <?php echo esc_html($category->name); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <noscript><input type="submit" value="Filter"></noscript>
        </form>
    </div>

    <div class="collections">
        <?php
        $collection_args = array(
            'post_type' => 'product_collection',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        );

        if (isset($_GET['category']) && !empty($_GET['category'])) {
            $collection_args['tax_query'] = array(
                array(
                    'taxonomy' => 'category',
                    'field' => 'term_id',
                    'terms' => intval($_GET['category']),
                ),
            );
        }

        $collections = new WP_Query($collection_args);

        if ($collections->have_posts()) {
            while ($collections->have_posts()) {
                $collections->the_post();
                ?>
                <div class="collection">
                    <h3><?php the_title(); ?></h3>
                    <?php if (has_post_thumbnail()) { ?>
                        <div class="collection-thumbnail">
                            <?php the_post_thumbnail('medium'); ?>
                        </div>
                    <?php } ?>
                    <p>Created on: <?php echo get_the_date(); ?></p>
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

<?php get_sidebar(); ?>
<?php get_footer(); ?>