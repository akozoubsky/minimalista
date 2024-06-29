<?php

/**
 * The searchform.php template.
 *
 * This template is used whenever the get_search_form() function is called.
 * It's a part of the WordPress search functionality.
 *
 * @link https://developer.wordpress.org/reference/functions/wp_unique_id/
 * @link https://developer.wordpress.org/reference/functions/get_search_form/
 *
 * @package LightCMSBootstrap
 * @version 1.0.0
 * @author Alexandre Kozoubsky
 */
?>

<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>" onsubmit="return validateSearch()">
    <label>
        <span class="screen-reader-text"><?php echo _x( 'Pesquisar por:', 'label', 'minimalista' ); ?></span>
        <input type="search" id="s" class="search-field" placeholder="<?php echo esc_attr_x( 'Pesquisar &hellip;', 'placeholder', 'minimalista' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
    </label>
    <input type="submit" class="search-submit" value="<?php echo esc_attr_x( 'Pesquisar', 'submit button', 'minimalista' ); ?>" />
</form>

<script>
	function validateSearch() {
		var searchInput = document.getElementById('s');
		if (searchInput.value.length < 3) {
			alert('Por favor, insira pelo menos 3 caracteres para a pesquisa.');
			return false;
		}
		return true;
	}
</script>