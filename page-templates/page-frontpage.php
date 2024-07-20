<?php
/**
 * Template Name: Front Page
 *
 * The template for displaying the front page.
 * 
 * Se o seu objetivo é ter uma página inicial estática que exiba uma combinação de conteúdo de página e posts recentes,
 * e você configurou essa página como a Front Page nas configurações de leitura, então "Front Page" é a escolha apropriada.
 * Ao usar "Front Page" como o nome do template, você deixa claro que este template é para ser usado como
 * a página inicial estática do site, conforme configurado em Configurações > Leitura. 
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
                // $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                //$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
                // captura a variável de paginação corretamente tanto para o contexto de query principal quanto para uma query personalizada.
                $paged = (get_query_var('paged')) ? get_query_var('paged') : (get_query_var('page') ? get_query_var('page') : 1);

				$args = array(
					'post_type' => 'post',
					//'category_name' => 'blog', // Slug da categoria
					'paged' => $paged,
                    'posts_per_page' => 3, // Limita o número de posts a 3
				);

				$blog_query = new WP_Query($args);

                if ($blog_query->have_posts()) {  ?>

                    <div class="row">

                        <?php
                        while ($blog_query->have_posts()) {
                            $blog_query->the_post();
                            $post_format = get_post_format() ?: 'standard';
                            $image_format = minimalista_get_featured_image_format(get_the_ID());
                            $image_size = 'thumbnail';
                            $image_classes = 'alignleft';
                            $show_thumbnail = true;
                            $show_excerpt = true;
                            $title_tag = 'h2';
                        ?>

                            <div class="col-12">

                                <article id="post-<?php the_ID(); ?>" <?php post_class("blog-posting"); ?> itemscope itemtype="http://schema.org/BlogPosting">

                                    <?php

                                    // Display title, summary, and image.
                                    echo '<header class="entry-header">';
                                    minimalista_display_post_title($title_tag, '', true);
                                    minimalista_display_post_metadata_primary('');
                                    echo '</header>';

                                    // Conditional display of the thumbnail
                                    if ($show_thumbnail) {
                                        minimalista_display_post_thumbnail($image_size, $image_classes, true);
                                    }

                                    // Conditional display the excerpt
                                    if ($show_excerpt) {
                                        minimalista_display_post_excerpt();
                                    }

                                    minimalista_display_post_metadata_secondary();
                                    ?>

                                </article><!-- /.blog-post -->

                            </div><!-- .col -->

                        <?php } // end while ?>

                    </div><!-- .row -->

                    <?php
                    //minimalista_custom_query_pagination($blog_query);  // Call the pagination function here

                    wp_reset_postdata();  // Restore the original post data
 
                } else {
                    ?>
                    <p><?php _e('Desculpe, não há postagens para exibir.'); ?></p>
                <?php } ?>

			</main><!-- #main -->
		</div>

		<!-- Right Sidebar Column -->
		<?php get_sidebar(); // Include the sidebar.php file ?>

	</div>
</div>

<?php
get_footer();
?>
