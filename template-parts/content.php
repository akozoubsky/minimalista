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

	<?php minimalista_post_thumbnail(); ?>

    <div class="entry-content">
        <?php
        if ( is_singular() ) {
			minimalista_display_post_content();
        } else {
			minimalista_display_post_excerpt();
        }

        wp_link_pages(
            array(
                'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'minimalista' ),
                'after'  => '</div>',
            )
        );
        ?>
    </div><!-- .entry-content -->

    <footer class="entry-footer">
        <?php minimalista_display_post_metadata_secondary(); ?>
    </footer><!-- .entry-footer -->
	
</article><!-- #post-<?php the_ID(); ?> -->
