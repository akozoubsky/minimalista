<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package minimalista
 */

get_header();
?>

<div class="container">
	<div class="row">
		<div class="col-lg-12">
			<main id="primary" class="site-main px-lg-3">

				<?php
				while ( have_posts() ) :
					the_post();

					get_template_part( 'template-parts/content', 'page-contato' );

					// If comments are open or we have at least one comment, load up the comment template.
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;

				endwhile; // End of the loop.
				?>

			</main><!-- #main -->
		</div>

		<!-- Right Sidebar Column -->
		<?php // get_sidebar(); // Include the sidebar.php file ?>
		
	</div>
</div>

<?php
get_footer();
?>
