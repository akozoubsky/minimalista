<?php
/**
 * Template part for displaying posts in a list or a single post
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package minimalista
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <header class="entry-header">
        <?php
        if ( is_singular() ) {
            minimalista_display_custom_header_image();
			minimalista_display_post_title('h1','entry-title', '');
		} else {
			minimalista_display_post_title('h2','entry-title', 'true');
		}

        if ( 'post' === get_post_type() ) :
            ?>
            <div class="entry-meta">
                <?php minimalista_display_post_metadata_primary(); ?>
            </div><!-- .entry-meta -->
        <?php endif; ?>
    </header><!-- .entry-header -->

	<?php minimalista_display_post_thumbnail("custom-thumbnail"); ?>

    <?php 
    $post_format = get_post_format() ?: 'standard';
    // Load specific template part based on the post format
    set_query_var('template_part_name', 'format-' . $post_format);
    get_template_part('template-parts/format/format', $post_format);
    minimalista_link_pages();
    ?>

    <footer class="entry-footer">
        <?php minimalista_display_post_metadata_secondary(); ?>
    </footer><!-- .entry-footer -->
	
</article><!-- #post-<?php the_ID(); ?> -->
