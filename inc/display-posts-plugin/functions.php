<?php
/**
 * Template Parts with Display Posts Shortcode
 *
 * @package minimalista
 * @since 1.0.0
 * @author Bill Erickson e Alexandre Kozoubsky
 * 
 * @link https://displayposts.com/  
 * @link https://www.billerickson.net/template-parts-with-display-posts-shortcode
 *
 * @param string $output, current output of post
 * @param array $original_atts, original attributes passed to shortcode
 * @return string $output
 *
 * Usage: [display-posts template="dps-widget-bs" wrapper="ul" wrapper_class="list-group" ignore_sticky_posts="true"]
 */

function minimalista_dps_template_part( $output, $original_atts ) {

	// Return early if our "content" attribute is not specified
	if( empty( $original_atts['template'] ) )
		return $output;
	ob_start();
	get_template_part( 'template-parts/content', $original_atts['template'] );
	$new_output = ob_get_clean();
	if( !empty( $new_output ) )
		$output = $new_output;
	return $output;
}
add_action( 'display_posts_shortcode_output', 'minimalista_dps_template_part', 10, 2 );

/** 
 * O shortcode [display-posts] não está incluindo o parâmetro image_size na consulta
 * Adiciona o filtro para capturar o parâmetro image_size dentro dos template-parts
 */
add_filter('display_posts_shortcode_args', 'dps_custom_image_size_param', 10, 2);
function dps_custom_image_size_param($args, $atts) {
    if (isset($atts['image_size'])) {
        $args['image_size'] = $atts['image_size'];
    }
    return $args;
}
?>