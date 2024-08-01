<?php

/**
 * The template for displaying 404 pages (not found)
 *
 * @package minimalista
 * @since 1.0.0
 * @author Alexandre Kozoubsky
 * 
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
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

				<section class="error-404 not-found">

					<header class="page-header">
						<h1 class="page-title"><?php esc_html_e('Oops! That page can&rsquo;t be found.', 'minimalista'); ?></h1>
					</header><!-- .page-header -->

					<div class="page-content">

						<p class="mb-3"><?php esc_html_e('It looks like nothing was found at this location. Maybe try one of the links below or a search?', 'minimalista'); ?></p>

						<section class="search-form">
							<?php get_search_form(); ?>
						</section>

						<section class="popular-posts">
							<h2 class="widget-title"><?php esc_html_e('Popular Posts', 'minimalista'); ?></h2>
							<?php minimalista_display_popular_articles(); ?>
						</section>

						<section class="widget widget_categories">
							<h2 class="widget-title"><?php esc_html_e('Most Used Categories', 'minimalista'); ?></h2>
							<ul>
								<?php
								wp_list_categories(
									array(
										'orderby'    => 'count',
										'order'      => 'DESC',
										'show_count' => 1,
										'title_li'   => '',
										'number'     => 10,
									)
								);
								?>
							</ul>
						</section><!-- .widget -->

						<section class="contact-info">
							<h2 class="widget-title"><?php esc_html_e('Need Help?', 'minimalista'); ?></h2>
							<p><?php esc_html_e('If you need further assistance, feel free to contact us.', 'minimalista'); ?></p>
							<p><a href="<?php echo esc_url(home_url('/contato')); ?>" class="btn btn-primary"><?php esc_html_e('Contact Us', 'minimalista'); ?></a></p>
						</section>

					</div><!-- .page-content -->

				</section><!-- .error-404 -->

			</main><!-- #main -->

		</div><!-- .col-lg-8 -->

	</div><!-- .row -->

</div>

<?php get_footer(); ?>
