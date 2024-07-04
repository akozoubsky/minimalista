<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package minimalista
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header">
		<?php
		if ( is_singular() ) :
			the_title( '<h1 class="entry-title">', '</h1>' );
		else :
			the_title( '<h2 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
		endif;

		if ( 'post' === get_post_type() ) :
			?>
			<div class="entry-meta">
				<?php
				//minimalista_posted_on();
				//minimalista_posted_by();
				minimalista_display_post_metadata_primary();
				?>
			</div><!-- .entry-meta -->
		<?php endif; ?>
		<?php minimalista_display_custom_header_image() ?>
	</header><!-- .entry-header -->

	<?php minimalista_post_thumbnail(); ?>

	<div class="entry-content">
		<?php
		/*
		 * A função the_excerpt() exibe o resumo do post. Dentro dela, o wp_kses() está sanitizando o texto que será exibido como o link "Continue reading".
		 * Neste caso, apenas a tag <span> com o atributo class é permitida.
		 */
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
		<?php minimalista_display_post_metadata_secondary(); ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
