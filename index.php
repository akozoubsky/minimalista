<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package minimalista
 */

// Prevent direct access to the file
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header();
?>

<div class="container">

	<div class="row">
	
		<div class="col-lg-8">
		
			<main id="primary" class="site-main">

				<?php
				if ( have_posts() ) :

					if ( is_home() && ! is_front_page() ) :
						?>

						<?php if (is_home() && get_option('page_for_posts')): // Verifica se a página atual é a página inicial e se há uma "Página para posts" definida.?>
							<header class="page-header">
							<h1 class="page-title"><?php single_post_title(); ?></h1>
							<?php
							/* 
								* Quando você define uma página específica para exibir seus posts, essa página geralmente não tem conteúdo próprio,
								* pois seu propósito é listar os posts do blog.
								* No entanto, você pode adicionar um resumo ou descrição a essa página para fornecer um contexto adicional ou uma introdução.
								* Se a verificação for verdadeira, exibe o resumo da "Página para posts" dentro de um parágrafo com a classe page-description.
								* add_post_type_support( 'page', 'excerpt' );
								*/
							?>
							<p class="page-description mt-3"><?php echo get_the_excerpt(get_option('page_for_posts')); ?></p>
							</header>
						<?php endif; ?>							
						
						<?php
					endif;					

					/* Start the Loop */
					while ( have_posts() ) :
						the_post();

						/*
						 * Include the Post-Type-specific template for the content.
						 * If you want to override this in a child theme, then include a file
						 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
						 */
						get_template_part( 'template-parts/content', get_post_type() );

					endwhile;

					minimalista_the_post_navigation();

				else :

					get_template_part( 'template-parts/content', 'none' );

				endif;
				?>

			</main><!-- #main -->
			
		</div><!-- ./col -->
		
		<!-- Right Sidebar Column -->
		<?php get_sidebar(); // Include the sidebar.php file ?>
		
	</div><!-- /.row -->

</div><!-- /.container -->

<?php
get_footer();
?>
