<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
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

<footer id="colophon" class="footer bg-light">
	<div class="container">
		<span class="text-muted">&copy; <?php echo date('Y'); ?> <?php echo get_bloginfo('name'); ?>. Todos os direitos reservados.</span>
	</div>
</footer><!-- #colophon -->
<?php wp_footer(); ?>
</body>
</html>
