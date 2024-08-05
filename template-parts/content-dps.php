<?php
/**
 * Template part with Display Posts Shortcode for displaying posts in a list or a single post
 *
 * @package Minimalista
 * @since 1.0.0
 * @author Alexandre Kozoubsky
 * 
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 * @link https://displayposts.com/2019/01/04/use-template-parts-to-match-your-themes-styling/
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <header class="entry-header">
        <?php
        if ( is_singular() ) {
            minimalista_display_custom_header_image();
			minimalista_display_post_title('h2','entry-title', 'true');
		} else {
			minimalista_display_post_title('h2','entry-title', 'true');
		}

        minimalista_display_post_metadata_primary();
        ?>
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
        <?php minimalista_display_post_metadata_secondary(''); ?>
    </footer>
    
</article><!-- #post-<?php the_ID(); ?> -->
