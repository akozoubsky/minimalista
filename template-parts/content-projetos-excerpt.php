<?php
/**
 * Template part for displaying posts da categoria projetos
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package minimalista
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header">
        <?php the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>

        <?php if ( 'post' === get_post_type() ) : ?>
            <div class="entry-meta">
                <?php
				//minimalista_posted_on();
				//minimalista_display_post_metadata_primary();
				//minimalista_posted_by();
				?>
            </div><!-- .entry-meta -->
        <?php endif; ?>
    </header><!-- .entry-header -->

    <?php minimalista_post_thumbnail(); ?>

    <div class="entry-content">
        <?php the_excerpt(); ?>
		<!--
        <a href="<?php the_permalink(); ?>" class="read-more-link" aria-label="<?php the_title_attribute(); ?>">
            Leia mais sobre "<?php the_title(); ?>"
        </a>
		-->

        <?php
        wp_link_pages(
            array(
                'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'minimalista' ),
                'after'  => '</div>',
            )
        );
        ?>
    </div><!-- .entry-content -->

    <footer class="entry-footer">
        <?php //minimalista_entry_footer(); ?>
        <?php //minimalista_display_post_metadata_secondary(); ?>
    </footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
