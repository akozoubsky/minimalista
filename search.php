<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package minimalista
 */

get_header();
?>

<div class="container">

	<div class="row"><!--  gx-5 gy-5 adicionam espaçamento horizontal e vertical entre as colunas, respectivamente -->

		<div class="col-lg-8">

			<main id="primary" class="site-main">

				<?php if ( have_posts() ) : ?>

					<header class="page-header mb-4">
						<h1 class="page-title">
							<?php
							/* translators: %s: search query. */
							printf( esc_html__( 'Search Results for: %s', 'minimalista' ), '<span>' . get_search_query() . '</span>' );
							?>
						</h1>
					</header><!-- .page-header -->

					<?php
					/* Start the Loop */
					while ( have_posts() ) :
						the_post();

						/**
						 * Run the loop for the search to output the results.
						 * If you want to overload this in a child theme then include a file
						 * called content-search.php and that will be used instead.
						 */
						get_template_part( 'template-parts/content', 'search' );

					endwhile;

					the_posts_navigation();

				else :

					get_template_part( 'template-parts/content', 'none' );

				endif;
				?>

			</main><!-- #main -->
	
		</div><!-- .col-lg-8 -->

		<!-- Right Sidebar Column -->
		<?php get_sidebar(); // Include the sidebar.php file ?>

	</div><!-- .row -->
	
</div>

<?php
get_footer();
?>
