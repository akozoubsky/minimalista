<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 * This template part includes the footer widgets and footer content.
 *
 * @package minimalista
 * @since 1.0.0
 * @author Alexandre Kozoubsky
 * 
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 */

// Prevent direct access to the file
if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
?>

</div><!-- #content .site-content -->

<?php
$active_widgets = 0;

if (is_active_sidebar('footer-1')) $active_widgets++;
if (is_active_sidebar('footer-2')) $active_widgets++;
if (is_active_sidebar('footer-3')) $active_widgets++;
if (is_active_sidebar('footer-4')) $active_widgets++;

// Define a largura das colunas baseado no número de widgets ativos
$md = ($active_widgets > 0) ? 12 / $active_widgets : 12;
$md = 3; /* fixed */
?>

<footer id="colophon" class="footer" itemscope itemtype="http://schema.org/WPFooter">

	<div class="container">

		<?php if ($active_widgets) : ?>

			<div class="footer-widgets">

				<div class="row">

					<?php if (is_active_sidebar('footer-1')) : ?>
						<div class="col-md-<?php echo intval($md); ?> col-sm-6 col-lg-<?php echo intval($md); ?> mx-auto">
							<div class="widget-area">
								<?php dynamic_sidebar('footer-1'); ?>
							</div>
						</div>
					<?php endif; ?>
					<?php if (is_active_sidebar('footer-2')) : ?>
						<div class="col-md-<?php echo intval($md); ?> col-sm-6 col-lg-<?php echo intval($md); ?> mx-auto">
							<div class="widget-area">
								<?php dynamic_sidebar('footer-2'); ?>
							</div>
						</div>
					<?php endif; ?>
					<?php if (is_active_sidebar('footer-3')) : ?>
						<div class="col-md-<?php echo intval($md); ?> col-sm-6 col-lg-<?php echo intval($md); ?> mx-auto">
							<div class="widget-area">
								<?php dynamic_sidebar('footer-3'); ?>
							</div>
						</div>
					<?php endif; ?>
					<?php if (is_active_sidebar('footer-4')) : ?>
						<div class="col-md-<?php echo intval($md); ?> col-sm-6 col-lg-<?php echo intval($md); ?> mx-auto">
							<div class="widget-area">
								<?php dynamic_sidebar('footer-4'); ?>
							</div>
						</div>
					<?php endif; ?>

				</div>

			</div><!-- ./footer-widgets -->

		<?php endif; ?>

	</div><!-- ./container -->

	<div class="footer-baseboard">

		<div class="container">

			<div class="copyright-info d-flex justify-content-between">
				<span>&copy; <?php echo date('Y'); ?> <?php if (get_bloginfo('name')) {
															echo minimalista_display_site_name('site-name', false) . '.';
														} ?> Todos os direitos reservados.</span>
				<?php if (get_the_privacy_policy_link()) : ?>
					<a href="<?php echo esc_url(get_privacy_policy_url()); ?>" itemprop="url">Política de privacidade</a>
				<?php endif; ?>
			</div>
			
		</div><!-- ./container -->

	</div>

</footer>

<?php wp_footer(); ?>
</body>

</html>