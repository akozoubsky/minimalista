<?php
/**
 * Template Name: Página de Projetos
 *
 * The template for displaying Projects.
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
					// Exibe o conteúdo da página.
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

				<?php
				$projetos_query = new WP_Query(array(
					'category_name' => 'projetos', // Slug da categoria
					'post_status'   => 'publish',
					'posts_per_page'=> 10,
				));

				if ($projetos_query->have_posts()) :
					while ($projetos_query->have_posts()) : $projetos_query->the_post();
						/*
						 * Include the Post-Type-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
						 */
						//get_template_part( 'template-parts/content', 'projetos' );
						?>

						<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

							<header class="entry-header">
								<?php
								minimalista_display_post_title('h2','entry-title', 'true');
						
								if ( 'post' === get_post_type() ) :
									?>
									<div class="entry-meta">
										<?php
										minimalista_display_post_metadata_primary();
										?>
									</div><!-- .entry-meta -->
								<?php endif; ?>
							</header><!-- .entry-header -->
						
							<?php minimalista_post_thumbnail(); ?>
						
							<div class="entry-content">
								<?php
								minimalista_display_post_content();
								minimalista_link_pages();
								?>
							</div><!-- .entry-content -->
						
							<footer class="entry-footer">
								<?php //minimalista_entry_footer(); ?>
								<?php minimalista_display_post_metadata_secondary(); ?>
							</footer><!-- .entry-footer -->

						</article><!-- #post-<?php the_ID(); ?> -->

					<?php
					endwhile;

					minimalista_custom_query_pagination($projetos_query);

				else :
					// get_template_part( 'template-parts/content', 'none' );
				endif;

				wp_reset_postdata();
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
