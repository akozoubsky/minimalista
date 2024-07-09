<?php
/**
 * Template Name: Home
 *
 * The template for displaying the home page.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 * @link https://github.com/oceanwp/oceanwp/blob/master/page.php
 *
 * @package minimalista
 */

// Prevent direct access to the file
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header(); ?>

<div class="container">
	<div class="row">
		<div class="col-lg-8">
			<main id="primary" class="site-main">

				<?php
				while (have_posts()) :
					// Exibe o conteúdo da página."
					the_post();

					get_template_part('template-parts/content', 'page');

					// If comments are open or we have at least one comment, load up the comment template.
					/* Nao permitir comentarios em paginas
					if (comments_open() || get_comments_number()) :
						comments_template();
					endif;
					*/

				endwhile; // End of the loop.
				?>

				<?php /* Exibe os posts do blog, de uma detrminada categoria */

				// Setting up the custom query to display blog posts
				$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
				$args = array(
					'post_type' => 'post',
					'category_name' => 'blog', // Slug da categoria
					'paged' => $paged
				);

				$blog_query = new WP_Query($args);

				if ($blog_query->have_posts()) {

					while ($blog_query->have_posts()) {
						$blog_query->the_post();
						$post_format = get_post_format() ?: 'standard';
						$image_format = minimalista_get_featured_image_format(get_the_ID());
						$image_size = 'custom-thumbnail';
						$image_classes = '';
						$show_thumbnail = true;
						$show_excerpt = true;
						$title_tag = 'h2';
				?>
						<article id="post-<?php the_ID(); ?>" <?php post_class("blog-posting"); ?> itemscope itemtype="http://schema.org/BlogPosting">

							<?php

							// Apply different designs based on the image format.
							switch ($image_format) {
								case 'landscape':
									// Landscape format design.

									// Display title, summary, and image.
									echo '<header class="entry-header">';
									minimalista_display_post_title($title_tag, '', true);
									minimalista_display_post_metadata_primary('');
									echo '</header><!-- ./header -->';
									// Conditional display of the thumbnail
									if ($show_thumbnail) {
										minimalista_display_post_thumbnail($image_size, $image_classes, true);
									}
									// Conditional display the excerpt
									if ($show_excerpt) {
										minimalista_display_post_content();
									}

									break;

								case 'portrait':
									// Portrait format design.

									// Conditional display of the thumbnail
									if ($show_thumbnail) {

										// Display image, title and summary.
										echo '<div class="row">';
										echo '<div class="col-4">';
										minimalista_display_post_thumbnail($image_size, $image_classes, true);
										echo '</div><!-- ./col-4 -->';
										echo '<div class="col-8">';
										echo '<header class="entry-header">';
										minimalista_display_post_title($title_tag, '', true);
										minimalista_display_post_metadata_primary('classe1 classe 2');
										echo '</header><!-- ./header -->';
										// Conditional display the excerpt
										if ($show_excerpt) {
											minimalista_display_post_content();
										}
										echo '</div><!-- .col-8 -->';
										echo '</div><!-- ./row -->';
									} else {
										echo '<header class="entry-header">';
										minimalista_display_post_title($title_tag, '', true);
										minimalista_display_post_metadata_primary();
										echo '</header><!-- ./header -->';
										// Conditional display the excerpt
										if ($show_excerpt) {
											minimalista_display_post_content();
										}
									}

									break;

								case 'square':
									// Square format design.

									// Conditional display of the thumbnail
									if ($show_thumbnail) {

										// Display image, title and summary.
										echo '<div class="row">';

										echo '<div class="col-4">';
										minimalista_display_post_thumbnail($image_size, $image_classes, true);
										echo '</div><!-- ./col-4 -->';
										echo '<div class="col-8">';
										echo '<header class="entry-header">';
										minimalista_display_post_title($title_tag, '', true);
										minimalista_display_post_metadata_primary();
										echo '</header><!-- ./header -->';
										// Conditional display the excerpt
										if ($show_excerpt) {
											minimalista_display_post_content();
										}
										echo '</div><!-- .col-8 -->';
										echo '</div><!-- ./row -->';
									} else {
										echo '<header class="entry-header">';
										minimalista_display_post_title($title_tag, '', true);
										minimalista_display_post_metadata_primary();
										echo '</header><!-- ./header -->';
										// Conditional display the excerpt
										if ($show_excerpt) {
											minimalista_display_post_content();
										}
									}

									break;

								default:
									// Fallback design if no featured image is found.
									echo '<header class="entry-header">';
									minimalista_display_post_title($title_tag, '', true);
									minimalista_display_post_metadata_primary();
									echo '</header><!-- ./header -->';
									// Conditional display the excerpt
									if ($show_excerpt) {
										minimalista_display_post_content();
									}
							}

							?>

						</article><!-- /.blog-post -->

					<?php
					}

					minimalista_custom_query_pagination($blog_query);  // Call the pagination function here

					wp_reset_postdata();  // Restore the original post data
					
				} else {
					?>
					<p><?php _e('Desculpe, não há postagens para exibir.'); ?></p>
				<?php }
				/* FIM Exibe os posts do blog, de uma detrminada categoria */
				?>


			</main><!-- #main -->
		</div>

		<!-- Right Sidebar Column -->
		<?php get_sidebar(); // Include the sidebar.php file 
		?>

	</div>
</div>

<?php
get_footer();
?>
