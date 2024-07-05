<?php
/**
 * minimalista functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package minimalista
 */

// Prevent direct access to the file
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
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
 * Custom template taxonomies tags for this theme.
 */
require get_template_directory() . '/inc/template-taxonomies.php';

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
//require get_template_directory() . '/classes/class-wp-bootstrap-navwalker.php';
require_once get_template_directory() . '/classes/Bootstrap_Nav_Walker.php';

/**
 * Functions related to images.
 */
require get_template_directory() . '/inc/functions-images.php';

/**
 * Functions related to metaboxes.
 */
require get_template_directory() . '/inc/metabox.php';

/**
 * Functions related to shorcodes.
 */
require get_template_directory() . '/inc/shortcodes.php';

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
add_action('pre_get_posts', 'minimalista_exclude_category_from_blog');

// ===========================================
// FORMULARIO DE CONTATO
// ===========================================

/**
 * Funcao de manipulacao do formulario de contato.
 * 
 * Este codigo ir processar o formulrio e enviar o email usando SMTP.
 * 
 * Passos:
 * 1. Configure o WP Mail SMTP para envio de emails.
 * 2. Crie um template de página com o formulário de contato HTML.
 * 3. Crie a página de contato e selecione o template.
 * 4. Cria as páginas de confirmação e Erro.
 * 5. Adicione o manipulador de formulário no functions.php. Use nomes únicos para funcoes e hooks para evitar conflitos.
 * 6. Crie páginas de confirmação e erro para redirecionamento após o envio do formulário.
 * 7. Teste Isoladamente: Desative outros plugins de formulário e teste seu formulário personalizado (Contact Form 7 e WPForms).
 * 8. Reative e Teste: Reative os plugins e teste novamente para garantir compatibilidade.
 * 9. Habilite Depuração: Use o modo de depuração do WordPress para identificar quaisquer conflitos ou erros.
 * 10. Verificar o Arquivo de Log wp-content/debug.log e o log do servidor /var/log/apache2/error.log.
 */

 function custom_handle_contact_form_submission() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        // Verifique o nonce
        if (!isset($_POST['custom_contact_form_nonce_field']) || !wp_verify_nonce($_POST['custom_contact_form_nonce_field'], 'custom_contact_form_nonce')) {
            wp_die('Erro na verificação do formulário.');
        }

        // Sanitizar e processar os dados do formulário
        $name = sanitize_text_field($_POST['name']);
        $email = sanitize_email($_POST['email']);
        $telefone_fixo = sanitize_text_field($_POST['telefone_fixo']);
        $celular = sanitize_text_field($_POST['celular']);
        $assunto = sanitize_text_field($_POST['assunto']);
        $message = sanitize_textarea_field($_POST['message']);

        // Configurações do email
        $to = 'contato@servirnomundo.org'; // Seu endereço de email
        $subject = 'Nova mensagem do formulário de contato';
        $headers = array('Content-Type: text/html; charset=UTF-8', 'From: ' . $name . ' <' . $email . '>');

        $body = "
            <h2>Nova mensagem do formulário de contato</h2>
            <p><strong>Nome:</strong> $name</p>
            <p><strong>Email:</strong> $email</p>
            <p><strong>Telefone Fixo:</strong> $telefone_fixo</p>
            <p><strong>Celular:</strong> $celular</p>
            <p><strong>Assunto:</strong> $assunto</p>
            <p><strong>Mensagem:</strong></p>
            <p>$message</p>
        ";

        // Enviar email
        if (wp_mail($to, $subject, $body, $headers)) {
            wp_redirect(home_url('/mensagem-enviada-com-sucesso'));
            exit;
        } else {
            wp_redirect(home_url('/problema-ao-enviar-sua-mensagem'));
            exit;
        }
    }
}
add_action('admin_post_nopriv_custom_send_contact_form', 'custom_handle_contact_form_submission');
add_action('admin_post_custom_send_contact_form', 'custom_handle_contact_form_submission');

function add_noindex_to_success_and_error_pages() {
    if (is_page('mensagem-enviada-com-sucesso') || is_page('problema-ao-enviar-sua-mensagem')) {
        echo '<meta name="robots" content="noindex, nofollow">';
    }
}
add_action('wp_head', 'add_noindex_to_success_and_error_pages');

// ===========================================
// END - FORMULARIO DE CONTATO
// ===========================================

// ==========================
// Gutenberg / Blocks - Keep this code at the end of the functions.php file
// ==========================

/**
 * The init hook is fired after WordPress has finished loading, but before any headers are sent.
 * This means that your function will be called very early in the page loading process, which is important to ensure that any actions
 * and filters you are removing are effectively disabled before they have a chance to be executed.
 */

 function theme_remove_gutenberg()
 {
 
     /* Classic Editor */
 
     // Gutenberg 5.3+
     remove_action('wp_enqueue_scripts', 'gutenberg_register_scripts_and_styles');
     remove_action('admin_enqueue_scripts', 'gutenberg_register_scripts_and_styles');
     remove_action('admin_notices', 'gutenberg_wordpress_version_notice');
     remove_action('rest_api_init', 'gutenberg_register_rest_widget_updater_routes');
     remove_action('admin_print_styles', 'gutenberg_block_editor_admin_print_styles');
     remove_action('admin_print_scripts', 'gutenberg_block_editor_admin_print_scripts');
     remove_action('admin_print_footer_scripts', 'gutenberg_block_editor_admin_print_footer_scripts');
     remove_action('admin_footer', 'gutenberg_block_editor_admin_footer');
     remove_action('admin_enqueue_scripts', 'gutenberg_widgets_init');
     remove_action('admin_notices', 'gutenberg_build_files_notice');
 
     remove_filter('load_script_translation_file', 'gutenberg_override_translation_file');
     remove_filter('block_editor_settings', 'gutenberg_extend_block_editor_styles');
     remove_filter('default_content', 'gutenberg_default_demo_content');
     remove_filter('default_title', 'gutenberg_default_demo_title');
     remove_filter('block_editor_settings', 'gutenberg_legacy_widget_settings');
     remove_filter('rest_request_after_callbacks', 'gutenberg_filter_oembed_result');
 
     // Previously used, compat for older Gutenberg versions.
     remove_filter('wp_refresh_nonces', 'gutenberg_add_rest_nonce_to_heartbeat_response_headers');
     remove_filter('get_edit_post_link', 'gutenberg_revisions_link_to_editor');
     remove_filter('wp_prepare_revision_for_js', 'gutenberg_revisions_restore');
 
     remove_action('rest_api_init', 'gutenberg_register_rest_routes');
     remove_action('rest_api_init', 'gutenberg_add_taxonomy_visibility_field');
     remove_filter('registered_post_type', 'gutenberg_register_post_prepare_functions');
 
     remove_action('do_meta_boxes', 'gutenberg_meta_box_save');
     remove_action('submitpost_box', 'gutenberg_intercept_meta_box_render');
     remove_action('submitpage_box', 'gutenberg_intercept_meta_box_render');
     remove_action('edit_page_form', 'gutenberg_intercept_meta_box_render');
     remove_action('edit_form_advanced', 'gutenberg_intercept_meta_box_render');
     remove_filter('redirect_post_location', 'gutenberg_meta_box_save_redirect');
     remove_filter('filter_gutenberg_meta_boxes', 'gutenberg_filter_meta_boxes');
 
     remove_filter('body_class', 'gutenberg_add_responsive_body_class');
     remove_filter('admin_url', 'gutenberg_modify_add_new_button_url'); // old
     remove_action('admin_enqueue_scripts', 'gutenberg_check_if_classic_needs_warning_about_blocks');
     remove_filter('register_post_type_args', 'gutenberg_filter_post_type_labels');
 
     /* Classic Widgets */
 
     // Disables the block editor from managing widgets in the Gutenberg plugin.
     add_filter('gutenberg_use_widgets_block_editor', '__return_false');
     // Disables the block editor from managing widgets.
     add_filter('use_widgets_block_editor', '__return_false');
 }
 // Enganche esta função ao hook 'init'
 add_action('init', 'theme_remove_gutenberg');
?>
