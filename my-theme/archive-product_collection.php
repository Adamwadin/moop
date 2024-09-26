<?php get_header(); ?>

<main>
    <h2>Our Collections</h2>



    <div class="collections">
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