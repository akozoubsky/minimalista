<?php
/**
 * Enqueue scripts and styles.
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function minimalista_scripts() {
	// função WordPress para registrar e enfileirar estilos. Ela toma cinco parâmetros: identificador único para o estilo, URL do arquivo de estilo, um array de dependências, versão e meios para os quais o estilo foi definido (como 'all', 'screen', 'print', etc.).	

    // Remove font awesome style from plugins.
    wp_deregister_style('font-awesome');
    wp_deregister_style('fontawesome');
	
    //wp_enqueue_style('bootstrap5', 'https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css');
    /* include the compiled Bootstrap CSS file */
    wp_enqueue_style('minimalista-bootstrap', trailingslashit(get_template_directory_uri()) . "assets/bootstrap/css/bootstrap.min.css", array(), '5.3.2');
	
    // @link https://fontawesome.com/download
    wp_enqueue_style('minimalista-fontawesome', trailingslashit(get_template_directory_uri()) . "assets/font-awesome/css/all.min.css", array(), '6.4.2');
	
    wp_enqueue_script('minimalista-bootstrap', trailingslashit(get_template_directory_uri()) . "assets/bootstrap/js/bootstrap.bundle.min.js", false, '5.3.2');

    wp_enqueue_script('minimalista-fontawesome', trailingslashit(get_template_directory_uri()) . "assets/font-awesome/js/all.min.js", true, '6.4.2');

    /**
     * CSS for Wrapping Text Around Images AND All Available WordPress Gallery Columns
     *
     * @link https://clicknathan.com/web-design/css-for-all-available-wordpress-gallery-columns/
     */
    wp_enqueue_style('image-and-gallery', trailingslashit(get_template_directory_uri()) . "css/style-image-and-gallery.css", array(), _S_VERSION);

    //wp_enqueue_script( 'minimalista-script', trailingslashit(get_template_directory_uri()) . '/js/minimalista.js', array(), _S_VERSION, true );

	wp_enqueue_style( 'minimalista-style', get_stylesheet_uri(), array('minimalista-bootstrap','minimalista-fontawesome'), _S_VERSION );
	wp_style_add_data( 'minimalista-style', 'rtl', 'replace' );

	//wp_enqueue_script( 'minimalista-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	
}
add_action( 'wp_enqueue_scripts', 'minimalista_scripts' );

/**
 * Dequeue Block Styles
 * @link https://smartwp.com/remove-gutenberg-css/
 */
function dequeue_block_styles()
{
    wp_dequeue_style('wp-block-library'); // Wordpress core
    wp_dequeue_style('wp-block-library-theme'); // Wordpress core
    wp_dequeue_style('wc-block-style'); // WooCommerce
    wp_dequeue_style('global-styles'); // Inline CSS
    wp_dequeue_style('storefront-gutenberg-blocks'); // Storefront theme
}
add_action( 'wp_enqueue_scripts', 'dequeue_block_styles', 100 );

// Dequeue Block Scripts
function dequeue_block_scripts() {
    wp_dequeue_script( 'wp-block-library' ); // Scripts padrão dos blocos
}
add_action( 'wp_enqueue_scripts', 'dequeue_block_scripts', 100 );

// Remover todos os elementos <style> que contenham estilos iniciando com "wp-block-"
function enqueue_remove_block_styles_script() {
    wp_enqueue_script('remove-wp-block-styles', get_template_directory_uri() . '/js/remove-wp-block-styles.js', array(), null, true);
}
add_action('wp_enqueue_scripts', 'enqueue_remove_block_styles_script');	

// Desabilitar jQuery no Frontend (mantendo no Admin)
function disable_jquery_on_frontend() {
    if (!is_admin()) {
        wp_deregister_script('jquery');
        wp_deregister_script('jquery-migrate');
    }
}

