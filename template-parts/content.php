<?php
/**
 * Template part for displaying posts in a list or a single post
 *
 * @package Minimalista
 * @since 1.0.0
 * @author Alexandre Kozoubsky
 * 
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <?php if ( is_singular() ) : ?>
        <header class="page-header">
            <?php minimalista_display_custom_header_image(); ?>
            <?php minimalista_display_post_title('h1','page-title', ''); ?>
            <?php minimalista_display_post_metadata_primary(); ?>
        </header>
    <?php else : ?>
        <header class="entry-header">
            <?php minimalista_display_post_title('h2','entry-title', 'true'); ?>
            <?php minimalista_display_post_metadata_primary(); ?>
        </header>
    <?php endif; ?>
                
    <?php if ( is_singular() ) : ?>
	    <?php minimalista_display_post_thumbnail('thumbnail', 'thumbnail alignleft', false); ?>
    <?php else : ?>
        <?php minimalista_display_post_thumbnail('thumbnail', 'thumbnail alignleft', true); ?>
    <?php endif; ?>

    <?php 
    $post_format = get_post_format() ?: 'standard';
    // Load specific template part based on the post format
    set_query_var('template_part_name', 'format-' . $post_format);
    get_template_part('template-parts/format/format', $post_format);
    minimalista_link_pages();
    ?>

    <?php minimalista_display_post_metadata_secondary(); ?>
	
</article><!-- #post-<?php the_ID(); ?> -->
