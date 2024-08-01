<?php
/**
 * Template Name: No Sidebar V.1
 * 
 * The template for displaying all pages without sidebar
 * 
 * @package Minimalista
 * @since 1.0.0
 * @author Alexandre Kozoubsky
 * 
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 */

// Prevent direct access to the file
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header();
?>

<div class="container">
	<div class="row">
		<div class="col-lg-12">
			<main id="primary" class="site-main">

				<?php
				while ( have_posts() ) :
					the_post();

					get_template_part( 'template-parts/content', 'page' );

					// If comments are open or we have at least one comment, load up the comment template.
					/* Nao permitir comentarios em paginas
					if ( comments_open() || get_comments_number() ) :
						comments_template();
					endif;
					*/

				endwhile; // End of the loop.
				?>

			</main><!-- #main -->
		</div>
		
	</div>
</div>

<?php
get_footer();
?>
