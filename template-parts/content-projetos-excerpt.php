<?php
/**
 * Template part for displaying posts projetos
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package minimalista
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php
		the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );

		if ( 'post' === get_post_type() ) :
			?>
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
		<?php
		the_excerpt(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'minimalista' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post( get_the_title() )
			)
		);

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
