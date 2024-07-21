<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package minimalista
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="page-header">
		<?php minimalista_display_custom_header_image(); ?>
		<?php minimalista_display_post_title('h1', 'page-title'); ?>
	</header><!-- .page-header -->

	<?php minimalista_display_post_thumbnail("custom-thumbnail"); ?>

	<?php
	minimalista_display_post_content();
	minimalista_link_pages();
	?>

	<?php minimalista_display_edit_post_link(); ?>	

</article><!-- #post-<?php the_ID(); ?> -->
