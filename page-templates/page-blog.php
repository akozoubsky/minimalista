<?php
/**
 * Template for displaying blog posts.
 *
 * This template file is designed for a specific page designated for blog posts.
 * It utilizes a custom query to fetch and display the blog posts, and integrates
 * Bootstrap styling to structure and style the content.
 *
 * Template Name: Blog
 *
 * @package Minimalista
 * @since 1.0.0
 * @author Alexandre Kozoubsky
 * 
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

            <main id="primary" class="site-main" itemscope itemtype="http://schema.org/Blog">
                
                <header class="page-header">
                    <?php minimalista_display_custom_header_image() ?>
                    <?php minimalista_display_post_title('h1', 'page-title'); ?>
                </header><!-- .entry-header -->

                <?php minimalista_display_post_thumbnail("custom-thumbnail"); ?>

                <?php
                minimalista_display_post_content();
                minimalista_link_pages();
                ?>

                <?php
                // Setting up the custom query to display blog posts
                $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                $args = array(
                    'post_type' => 'post',
                    'paged' => $paged
                );

                $blog_query = new WP_Query($args);

                if ($blog_query->have_posts()) {  ?>

                    <div class="row">

                        <?php
                        while ($blog_query->have_posts()) {
                            $blog_query->the_post();
                            $post_format = get_post_format() ?: 'standard';
                            $image_format = minimalista_get_featured_image_format(get_the_ID());
                            $image_size = 'thumbnail';
                            $image_classes = 'alignleft';
                            $show_thumbnail = true;
                            $show_excerpt = true;
                            $title_tag = 'h2';
                            $post_format = get_post_format() ?: 'standard';

                        ?>

                            <div class="col-12">

                                <article id="post-<?php the_ID(); ?>" <?php post_class("blog-posting article-component article-component-" . $post_format); ?> itemscope itemtype="http://schema.org/BlogPosting">

                                    <?php
                                    echo '<div class="article-component-header">';
                                    echo '<header class="entry-header">';
                                    minimalista_display_post_title($title_tag, '', true);
                                    minimalista_display_post_metadata_primary('');
                                    echo '</header>';
                                    echo '</div><!-- .article-component-header -->';

                                    echo '<div class="article-component-content">';
                                    // Conditional display of the thumbnail
                                    if ($show_thumbnail) {
                                        minimalista_display_post_thumbnail($image_size, $image_classes, true);
                                    }

                                    // Conditional display the excerpt
                                    if ($show_excerpt) {
                                        //minimalista_display_post_excerpt();
                                        // Load specific template part based on the post format
                                        set_query_var('template_part_name', 'format-' . $post_format);
                                        get_template_part('template-parts/format/format', $post_format);
                                        minimalista_link_pages();
                                    }
                                    echo '</div><!-- .article-component-content -->';
       
                                    echo '<div class="article-component-footer">';
                                    echo '<footer class="entry-footer">';
                                    minimalista_display_post_metadata_secondary('');
                                    echo '</footer>';
                                    echo '</div><!-- .article-component-footer -->';
                                    ?>

                                </article><!-- /.blog-post -->

                            </div><!-- .col -->

                        <?php } // end while ?>

                    </div><!-- .row -->

                    <?php
                    minimalista_custom_query_pagination($blog_query);  // Call the pagination function

                    wp_reset_postdata();  // Restore the original post data
 
                } else {
                    ?>
                    <p><?php _e('Desculpe, não há postagens para exibir.'); ?></p>
                <?php } ?>
                
            </main><!-- /.blog-main -->

        </div><!-- ./col- -->

		<!-- Right Sidebar Column -->
		<?php get_sidebar(); // Include the sidebar.php file ?>

    </div><!-- /.row -->

</div><!-- /.container -->

<?php
get_footer();  // Include the footer.php file
?>