<?php
/**
 * Template for displaying blog posts in cards content caontainer.
 *
 * This template file is designed for a specific page designated for blog posts.
 * It utilizes a custom query to fetch and display the blog posts, and integrates
 * Bootstrap styling to structure and style the content.
 *
 * Template Name: Blog Cards
 *
 * @package WordPress
 * @subpackage Minimalista
 * @since 1.0.0
 * @author Alexandre Kozoubsky
 * @link https://getbootstrap.com/docs/5.3/examples/blog/
 */

// Prevent direct access to the file
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header();  // Include the header.php file
?>

<div class="container">

    <div class="row">

        <!-- Main Content Column -->
        <div class="col-lg-8 ">

            <main id="main" class="blog-main" itemscope itemtype="http://schema.org/Blog">

                <header class="page-header">
                    <?php minimalista_display_post_title('h1', 'entry-title'); ?>
                    <?php minimalista_display_custom_header_image() ?>
                </header>

                <?php
                // Setting up the custom query to display blog posts
                $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                $args = array(
                    'post_type' => 'post',
                    'paged' => $paged
                );
                $blog_query = new WP_Query($args);

                if ($blog_query->have_posts()) {

                    echo '<div class="row">'; // Start of row container for the posts

                    while ($blog_query->have_posts()) {

                        $blog_query->the_post();
                        $post_format = get_post_format() ?: 'standard';
                        $image_format = minimalista_get_featured_image_format(get_the_ID());
                        $image_size = 'custom-thumbnail';
                        $image_classes = 'thumbnail img-fluid';
                        $show_thumbnail = true;
                        $show_excerpt = true;
                        $title_tag = 'h2';
                ?>
                        <div class="col-sm-6">

                            <article id="post-<?php the_ID(); ?>" <?php post_class('card'); ?> itemscope itemtype="http://schema.org/BlogPosting">

                                <div class="card-body"> <!-- Start of card body -->

                                    <header class="entry-header">
                                        <!-- Post Title -->
                                        <?php minimalista_display_post_title('card-title', $title_tag, 'true'); ?>
                                        <?php minimalista_display_post_metadata_primary("mb-3"); ?>
                                    </header>

                                    <?php
                                    if ($show_thumbnail)
                                        minimalista_display_post_thumbnail($image_size, $image_classes, true);
                                    ?>

                                    <?php
                                    if ($show_excerpt)
                                        minimalista_display_post_excerpt('card-text mt-3');
                                    ?>

                                    <a href="<?php the_permalink(); ?>" class="continue-reading icon-link gap-1 icon-link-hover mt-2">Continue lendo<i class="fas fa-angle-right"></i></a>

                                </div><!-- ./ card-body -->

                            </article><!-- /.blog-post -->

                        </div><!-- ./col -->

                    <?php

                    } //end while

                    echo '</div>'; // End of row container for the posts

                    minimalista_custom_query_pagination($blog_query);  // Call the pagination function here

                    wp_reset_postdata();  // Restore the original post data

                } else {
                    ?>
                    <p><?php _e('Sorry, no posts to display.'); ?></p>
                <?php } ?>

            </main><!-- /.blog-main -->

        </div>

        <!-- Sidebar Column -->
        <aside id="secondary" class="widget-area sidebar-right col-lg-4 mt-5 mt-lg-3 ps-lg-5">
            <?php minimalista_display_categories('Tópicos', '', 'widget', 'my-1', 'Ir para o Blog', 'fw-bold', '', '') ?>
            <?php minimalista_display_popular_articles(0, 'Artigos Populares', '', 'widget', 'my-1'); ?> 
        </aside><!-- #secondary -->

    </div><!-- /.row -->

</div><!-- /.container -->

<?php
get_footer();  // Include the footer.php file
?>