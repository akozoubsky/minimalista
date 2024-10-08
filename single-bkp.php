<?php
/**
 * The template for displaying all single posts
 *
 * @package minimalista
 * @since 1.0.0
 * @author Alexandre Kozoubsky
 * 
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 */

get_header();
?>

<div class="container">
	<div class="row">
		<div class="col-lg-8">
			<main id="primary" class="site-main">

				<?php
				while ( have_posts() ) :
					the_post();

					get_template_part( 'template-parts/content', get_post_type() );

					/* Must be used in The Loop. */
					//minimalista_single_post_navigation();

					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;

				endwhile; // End of the loop.
				?>

				<?php //minimalista_display_related_posts_by_tags(5, 'dps-widget-bs', 'ul', 'list-group'); ?>
				<?php minimalista_display_related_posts_by_tags(4, 'dps-widget-bs-related-posts', 'div', 'row row-cols-1 row-cols-md-2 g-4'); ?>

			</main><!-- #main -->
		</div>

		<?php get_sidebar(); ?>

	</div>
</div>

<?php
get_footer();
?>
