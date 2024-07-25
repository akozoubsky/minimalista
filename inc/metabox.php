<?php
/**
 * minimalista Metabox
 *
 * @package minimalista
 * @since 1.0.0
 * @author Alexandre Kozoubsky
 */

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
        __('Desativar parágrafo automático', 'minimalista'),    // Título do metabox
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
    echo ' ' . __('Desabilitar wpautop', 'minimalista');
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
// Metabox - Custom Post and Page Header Image 
// ===========================================

add_action('add_meta_boxes', 'custom_header_image_meta_box');

function custom_header_image_meta_box()
{
    add_meta_box(
        'custom_header_image',                  // ID do metabox
        'Imagem de Cabeçalho Personalizada',    // Título do metabox
        'custom_header_image_callback',         // Callback para renderizar o conteúdo do metabox
        ['post', 'page'],                       // Telas onde o metabox será exibido (posts e páginas)
        'side',                                 // Contexto (lateral)
        'default'                               // Prioridade
    );
}

function custom_header_image_callback($post)
{
    $custom_header_image = get_post_meta($post->ID, 'custom_header_image_id', true);

    // Adiciona nonce para segurança
    wp_nonce_field('custom_header_image_nonce', 'custom_header_image_nonce_field');

    // Pré-visualização da imagem
    if ($custom_header_image) {
        echo '<img id="custom_header_image_preview" class="custom_header_image" src="' . wp_get_attachment_image_url($custom_header_image, 'custom-thumbnail') . '" style="max-width:100%; max-height: 300px; height: auto; display:block;">';
    } else {
        echo '<img id="custom_header_image_preview" class="custom_header_image" style="max-width:100%; display:none;">';
    }

    // Campo para upload de imagem
    echo '<input type="hidden" id="custom_header_image_id" name="custom_header_image_id" value="' . esc_attr($custom_header_image) . '">';
    echo '<button id="upload_custom_header_image_button" style="margin-top: 1rem; margin-right: 0.5rem; margin-bottom: 1rem; padding: 0.3rem;">Selecionar Imagem</button>';
    echo '<button id="remove_custom_header_image_button" style="margin-top: 1rem; margin-bottom: 1rem; padding: 0.3rem;' . ($custom_header_image ? '' : 'display:none;') . '">Remover Imagem</button>';

    // JavaScript para o upload de imagem
?>
    <script>
        jQuery(document).ready(function($) {
            $('#upload_custom_header_image_button').click(function(e) {
                e.preventDefault();

                var custom_uploader = wp.media({
                    title: 'Selecionar imagem',
                    button: {
                        text: 'Usar esta imagem'
                    },
                    multiple: false
                }).on('select', function() {
                    var attachment = custom_uploader.state().get('selection').first().toJSON();
                    $('#custom_header_image_id').val(attachment.id);

                    // Atualizar a pré-visualização da imagem
                    if (attachment.url) {
                        $('#custom_header_image_preview').attr('src', attachment.url).show();
                    }
                }).open();
            });

            $('#remove_custom_header_image_button').click(function(e) {
                e.preventDefault();

                $('#custom_header_image_id').val('');
                $('#custom_header_image_preview').hide();
                $(this).hide();
            });
        });
    </script>
<?php
}

add_action('save_post', 'save_custom_header_image_meta_box_data');

function save_custom_header_image_meta_box_data($post_id)
{
    // Verificar se o nonce é válido
    if (!isset($_POST['custom_header_image_nonce_field']) || !wp_verify_nonce($_POST['custom_header_image_nonce_field'], 'custom_header_image_nonce')) {
        return;
    }

    // Salvar ou remover o ID da imagem no post meta
    if (isset($_POST['custom_header_image_id'])) {
        if ($_POST['custom_header_image_id']) {
            update_post_meta($post_id, 'custom_header_image_id', sanitize_text_field($_POST['custom_header_image_id']));
        } else {
            delete_post_meta($post_id, 'custom_header_image_id');
        }
    }
}

/**
 * Retrieves custom header image URL.
 *
 * @param int|null $post_id Optional. Post ID. Default is null which means current post in the loop.
 * @return string|false The image URL or false if no image.
 */
function get_custom_header_image_url($post_id = null)
{
    // Se $post_id for fornecido, ele é convertido para inteiro usando intval(). Se não for fornecido, get_the_ID() é usado para obter o ID do post atual
    $post_id = $post_id ? intval($post_id) : get_the_ID();

    if (!$post_id) {
        return false; // Retorna false se não estiver no loop e nenhum post_id for fornecido.
    }

    $custom_header_image_id = get_post_meta($post_id, 'custom_header_image_id', true);
    if ($custom_header_image_id) {
        return wp_get_attachment_image_url($custom_header_image_id, 'full');
    }

    return false; // Retorna false se não houver imagem de cabeçalho personalizada.
}

/**
 * Displays the custom header image for a specific post or the current post in the loop.
 *
 * @param int|null $post_id Optional. Post ID. Default is null which means current post in the loop.
 */
function minimalista_display_custom_header_image($post_id = null)
{
    // Se $post_id for fornecido, ele é convertido para inteiro usando intval(). Se não for fornecido, get_the_ID() é usado para obter o ID do post atual
    $post_id = $post_id ? intval($post_id) : get_the_ID();

    $image_url = get_custom_header_image_url($post_id);
    if ($image_url) {
        // Se nenhum post_id foi fornecido, assume-se que está no loop
        $alt_text = $post_id ? get_the_title($post_id) : get_the_title();

        echo '<div class="post-header-image">';
        echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($alt_text) . '" itemprop="primaryImageOfPage">';
        echo '</div>';
    }
}

// ===========================================
// END Metabox - Custom Post and Page Header Image 
// ===========================================

// ===========================================
// END METABOXES
// ===========================================