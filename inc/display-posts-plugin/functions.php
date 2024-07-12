<?php
/**
 * Template Parts with Display Posts Shortcode
 * @author Bill Erickson
 * @link https://displayposts.com/  
 * @link https://www.billerickson.net/template-parts-with-display-posts-shortcode
 *
 * @param string $output, current output of post
 * @param array $original_atts, original attributes passed to shortcode
 * @return string $output
 *
 * Usage: [display-posts content="dps" content="post" wrapper="div" wrapper_class="dps_post_list" ignore_sticky_posts="true"]
 */
function minimalista_dps_template_part( $output, $original_atts ) {

	// Return early if our "content" attribute is not specified
	if( empty( $original_atts['content'] ) )
		return $output;
	ob_start();
	get_template_part( 'template-parts/content', $original_atts['content'] );
	$new_output = ob_get_clean();
	if( !empty( $new_output ) )
		$output = $new_output;
	return $output;
}
add_action( 'display_posts_shortcode_output', 'minimalista_dps_template_part', 10, 2 );
?>