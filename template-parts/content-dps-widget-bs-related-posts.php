<?php

/**
 * Template part with Display Posts Shortcode for displaying posts in a Bootstrap Cards.
 *
 * @package Minimalista
 * @since 1.0.0
 * @author Alexandre Kozoubsky
 * 
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 * @link https://displayposts.com/2019/01/04/use-template-parts-to-match-your-themes-styling/
 * @link https://getbootstrap.com/docs/5.3/components/card/
 */

// Verifica se a variável global $dps_listing existe e contém o parâmetro image_size
global $dps_listing;
$image_size = false; // Inicializa como false

// Verifica se $dps_listing é um objeto e se contém a propriedade query_vars
if ( is_object( $dps_listing ) && isset( $dps_listing->query_vars['image_size'] ) ) {
    $image_size = $dps_listing->query_vars['image_size'];
}

?>

<div class="col">
<div class="card h-100">
    <?php
    // Recupera o título do post
    $post_title = get_the_title();

    // Recupera o link permanente do post
    $post_link = get_permalink();

    // Verifica se o post tem uma imagem destacada e se o image_size foi definido
    if ($image_size && has_post_thumbnail()) {
        // Gera o HTML do link com a imagem destacada
        echo '<a class="card-img-top" href="' . esc_url($post_link) . '">';
        the_post_thumbnail($image_size);
        echo '</a>';
    }

	echo '<div class="card-body">';
		echo '<h6 class="card-title mb-0">';
		// Gera o HTML do link com o título do post
		echo '<a href="' . esc_url($post_link) . '">' . esc_html($post_title) . '</a>';
		echo '</h6>';
		//echo '<div class="card-text mb-3">';
		//minimalista_display_post_excerpt();
		//echo '</div>';
	echo '</div>';
	?>

</div><!-- ./card-item -->
</div>
