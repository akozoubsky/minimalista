<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package minimalista
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header">
		<?php minimalista_display_post_title('h2','entry-title', 'true'); ?>

		<?php if ( 'post' === get_post_type() ) : ?>
		<div class="entry-meta">
			<?php minimalista_display_post_metadata_primary(); ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>
	</header><!-- .entry-header -->

	<?php minimalista_display_post_thumbnail("custom-thumbnail"); ?>

	<div class="entry-summary">
		<?php minimalista_display_post_excerpt(); ?>
	</div><!-- .entry-summary -->

	<footer class="entry-footer">
		<?php minimalista_entry_footer(); ?>
	</footer><!-- .entry-footer -->
	
</article><!-- #post-<?php the_ID(); ?> -->
