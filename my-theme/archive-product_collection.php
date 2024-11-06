<?php get_header(); ?>

<main>
    <h2>Product Collections</h2>

    <div class="sorting-options">
        <?php
        $current_url = home_url(add_query_arg(array(), $_SERVER['REQUEST_URI']));

        $sort_by_date_desc_url = add_query_arg(array('sort_by' => 'date', 'order' => 'DESC'), $current_url);
        $sort_by_date_asc_url = add_query_arg(array('sort_by' => 'date', 'order' => 'ASC'), $current_url);

        echo '<a href="' . esc_url($sort_by_date_desc_url) . '">Sort by Date Descending</a>';
        echo ' | ';
        echo '<a href="' . esc_url($sort_by_date_asc_url) . '">Sort by Date Ascending</a>';
        ?>
    </div>

    <div class="collections">
        <?php
        $args = array(
            'post_type' => 'product_collection',
            'posts_per_page' => -1,
            'post_status' => 'publish',
        );

        if (isset($_GET['sort_by']) && $_GET['sort_by'] === 'date') {
            $args['orderby'] = 'date';
            $args['order'] = isset($_GET['order']) ? $_GET['order'] : 'ASC';
        }

        $collections = new WP_Query($args);

        if ($collections->have_posts()) {
            while ($collections->have_posts()) {
                $collections->the_post();
                ?>
                <div class="collection">
                    <h4><?php the_title(); ?></h4>
                    <p><strong>Created on:</strong> <?php echo get_the_date(); ?></p>

                    <?php
                    $categories = get_the_terms(get_the_ID(), 'category');
                    if ($categories && !is_wp_error($categories)) {
                        echo '<p><strong>Categories:</strong> ';
                        $category_names = wp_list_pluck($categories, 'name');
                        echo implode(', ', $category_names);
                        echo '</p>';
                    }
                    ?>
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

<?php get_sidebar(); ?>
<?php get_footer(); ?>