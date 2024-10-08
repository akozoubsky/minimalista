<?php
/**
 * The searchform.php template.
 *
 * This template is used whenever the get_search_form() function is called.
 * It's a part of the WordPress search functionality.
 *
 * @package minimalista
 * @since 1.0.0
 * @author Alexandre Kozoubsky
 * 
 * @link https://developer.wordpress.org/reference/functions/wp_unique_id/
 * @link https://developer.wordpress.org/reference/functions/get_search_form/
 */

// Prevent direct access to the file
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>" onsubmit="return validateSearch()">
    <div class="input-group">
        <input type="search" id="s" class="form-control search-field" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'minimalista' ); ?>" value="<?php echo get_search_query(); ?>" name="s" aria-label="<?php echo _x( 'Search for', 'label', 'minimalista' ); ?>" />
        <button type="submit" class="btn btn-primary search-submit"><?php echo esc_attr_x( 'Search', 'submit button', 'minimalista' ); ?></button>
    </div>
</form>

<script>
	function validateSearch() {
		var searchInput = document.getElementById('s');
		if (searchInput.value.length < 3) {
			alert('Por favor, insira pelo menos 3 caracteres para a pesquisa. Please enter at least 3 characters to search.');
			return false;
		}
		return true;
	}
</script>