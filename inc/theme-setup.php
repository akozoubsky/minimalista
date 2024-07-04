<?php
/**
 * Theme setup and custom theme supports.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function minimalista_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on minimalista, use a find and replace
		* to change 'minimalista' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'minimalista', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'minimalista' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'minimalista_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
    $defaults_custom_logo = array(
        'height'      => 512,
        'width'       => 512,
        'flex-height' => true,
        'flex-width'  => true,
        'header-text' => array( 'site-title', 'site-description' ),
    );
    add_theme_support( 'custom-logo', $defaults_custom_logo );
	
	// Add support for responsive embedded content.
	add_theme_support( 'responsive-embeds' );

	// Habilitar a caixa de resumo para paginas
	add_post_type_support( 'page', 'excerpt' );

	// Remove support for editor styles.
	remove_theme_support( 'editor-styles' );

	/**
	 * WordPress comes with a number of block patterns built-in, themes can opt-out of the bundled patterns and provide their own set using the following code
	 *
	 * @link https://developer.wordpress.org/block-editor/how-to-guides/themes/theme-support/#disabling-the-default-block-patterns
	 */
	remove_theme_support( 'core-block-patterns' );

	// Remove blank SVGs 
	remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );		

	// Enable shortcodes in widgets.
	add_filter('widget_text', 'do_shortcode');		

	// Remove feed icon link from legacy RSS widget.
	add_filter( 'rss_widget_feed_link', '__return_false' );

	// Do not use WordPress default gallery style
	add_filter( 'use_default_gallery_style', '__return_false' );	

	// Fully Disable Gutenberg editor.
	add_filter( 'use_block_editor_for_post_type', '__return_false', 10 );

	/* A função remove_wp_block_inline_styles faz uso de ob_start() e ob_get_clean() para capturar o HTML de saída. Então, ela utiliza a função preg_replace para encontrar e remover todas as ocorrências de estilos inline que comecem com ".wp-block-" antes que o HTML seja enviado ao navegador.
	Esta abordagem pode ter implicações de desempenho, pois manipula o HTML de saída inteiro. Além disso, é uma solução um tanto "bruta", que pode ter efeitos colaterais indesejados, dependendo de como os estilos inline estão sendo usados em seu site. 
	*/
	function remove_wp_block_inline_styles() {
		// Iniciar o buffer de saída
		ob_start(function ($buffer) {
			// Usar expressão regular para encontrar e remover estilos inline que começam com .wp-block-
			$pattern = '/<style type=[\'"]text\/css[\'"]>.*?\.wp-block-.*?<\/style>/s';
			return preg_replace($pattern, '', $buffer);
		});
	}
	// Adicionar a ação ao gancho 'wp_head' para iniciar o buffer antes de qualquer saída ser enviada.
	//add_action('wp_head', 'remove_wp_block_inline_styles');
	add_action('wp_footer', 'remove_wp_block_inline_styles');

}
add_action( 'after_setup_theme', 'minimalista_setup' );