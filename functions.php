<?php
/**
 * minimalista functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package minimalista
 */
if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
/* 1140px é a largura máxima do container padrão do Bootstrap para telas grandes. Esta largura pode ser ajustada conforme necessário para corresponder ao design específico do seu tema.
A função apply_filters permite que outros desenvolvedores ou plugins ajustem esse valor se necessário, mantendo a flexibilidade.
*/
function minimalista_content_width() {
	// Set the default content width based on the Bootstrap container width
	$GLOBALS['content_width'] = apply_filters( 'minimalista_content_width', 1140 );
}
add_action( 'after_setup_theme', 'minimalista_content_width', 0 );

// Include theme setup functions.
require get_template_directory() . '/inc/theme-setup.php';

// Include enqueue scripts functions.
require get_template_directory() . '/inc/enqueue-scripts.php';

// Include widgets functions.
require get_template_directory() . '/inc/widgets.php';

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Bootstrap Nav Walker.
 */
require get_template_directory() . '/inc/class-wp-bootstrap-navwalker.php';

/**
 * Functions related to images.
 */
require get_template_directory() . '/inc/functions-images.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

// ===========================================
// Funcoes relativas a segurança do site
// ===========================================

// Desativar o JSON REST API para usuarios nao autenticados
add_filter('rest_authentication_errors', function($result) {
    if (!empty($result)) {
        return $result;
    }
    if (!is_user_logged_in()) {
        return new WP_Error('rest_not_logged_in', 'Você deve estar logado para acessar a API REST.', array('status' => 401));
    }
    return $result;
});

// ===========================================
// Metabox - WPAUTOP
// ===========================================

/**
 * Adds a meta box to the WordPress post editing screen to allow users to disable automatic paragraph formatting (wpautop).
 * This feature gives users, especially those inserting custom HTML, more control over post formatting.
 * The meta box includes a checkbox for users to toggle the wpautop feature for individual posts.
 */
function minimalista_wpautop_control_meta_box()
{
    add_meta_box(
        'wpautop_control',                                              // ID do metabox
        __('Desativar parágrafo automático', 'light-cms-bootstrap'),    // Título do metabox
        'minimalista_wpautop_control_callback',                                     // Callback para renderizar o conteúdo do metabox
        ['post', 'page'],                                               // Telas onde o metabox será exibido (posts e páginas)
        'side',                                                         // Contexto (lateral)
        'default'                                                       // Prioridade
    );
}

/**
 * Callback function for the meta box.
 *
 * @param WP_Post $post The post object.
 */
function minimalista_wpautop_control_callback($post)
{
    // Nonce field for security
    wp_nonce_field(basename(__FILE__), 'wpautop_nonce');

    $value = get_post_meta($post->ID, '_disable_wpautop', true);
    echo '<label>';
    echo '<input type="checkbox" name="disable_wpautop" value="1" ' . checked($value, '1', false) . '>';
    echo ' ' . __('Desabilitar wpautop', 'light-cms-bootstrap');
    echo '</label>';
}

add_action('add_meta_boxes', 'minimalista_wpautop_control_meta_box');

/**
 * Save the meta box content.
 *
 * @param int $post_id The ID of the post being saved.
 */
function save_minimalista_wpautop_control_meta_box($post_id)
{
    // Verify nonce
    if (!isset($_POST['wpautop_nonce']) || !wp_verify_nonce($_POST['wpautop_nonce'], basename(__FILE__))) {
        return;
    }

    // Prevent autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check user's permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Update the meta field
    if (isset($_POST['disable_wpautop'])) {
        update_post_meta($post_id, '_disable_wpautop', '1');
    } else {
        delete_post_meta($post_id, '_disable_wpautop');
    }
}

add_action('save_post', 'save_minimalista_wpautop_control_meta_box');

/**
 * Disable wpautop if the custom field is checked.
 *
 * @param string $content Content of the post.
 * @return string Modified content.
 */
function minimalista_disable_wpautop_conditionally($content)
{
    if (is_singular()) {
        global $post;
        if (get_post_meta($post->ID, '_disable_wpautop', true) === '1') {
            remove_filter('the_content', 'wpautop');
        }
    }

    return $content;
}

add_filter('the_content', 'minimalista_disable_wpautop_conditionally', 0);

// ===========================================
// END Metabox - WPAUTOP
// ===========================================

// ===========================================
// END METABOXES
// ===========================================

/**
 * Modificar a consulta principal do WordPress para evitar que os posts na categoria "Projetos" apareçam nas listagens do blog
 * is_home(): Verifica se estamos na página inicial do blog.
 * is_main_query(): Certifica-se de que estamos modificando a consulta principal e não uma consulta secundária.
 * get_cat_ID('Projetos'): Obtém o ID da categoria "Projetos" usando seu nome. Certifique-se de que "Projetos" corresponde ao nome exato da categoria.
 * '-' . get_cat_ID('Projetos'): O sinal de menos (-) antes do ID da categoria indica que queremos excluir essa categoria da consulta.
 */
function minimalista_exclude_category_from_blog($query) {
    if ($query->is_home() && $query->is_main_query()) {
        $query->set('cat', '-' . get_cat_ID('Projetos')); // Exclui a categoria com o slug 'Projetos'
    }
}
add_action('pre_get_posts', 'minimalista_exclude_category_from_blog');?>