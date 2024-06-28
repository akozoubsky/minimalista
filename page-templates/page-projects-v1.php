<?php
/**
 * Template Name: Página de Projetos V.1
 *
 * The template for displaying Projects.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 * @link https://github.com/oceanwp/oceanwp/blob/master/page.php
 *
 * @package minimalista
 */

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
					if (comments_open() || get_comments_number()) :
						comments_template();
					endif;

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
						get_template_part( 'template-parts/content', get_post_type() );	
					endwhile;

					the_posts_navigation();

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
