<?php
/**
 * Template part for displaying page content in page-frontpage-full.php
 *
 * Não exibe <header></header> e com isso, a imagem de cabeçalho nem o título da página (o que deve ficar a cargo do post).
 * Também não exibe post-thumbnail
 * 
 * @package Minimalista
 * @since 1.0.0
 * @author Alexandre Kozoubsky
 * 
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
	minimalista_display_post_content();
	minimalista_link_pages();
	?>

	<?php minimalista_display_edit_post_link(); ?>

</article><!-- #post-<?php the_ID(); ?> -->
