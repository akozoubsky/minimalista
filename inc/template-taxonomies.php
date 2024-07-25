<?php
/**
 * Custom template taxonomies tags for this theme
 *
 * Esse arquivo geralmente é usado para definir funções personalizadas que são usadas dentro dos templates do seu tema para exibir conteúdo específico.
 * Essas funções geralmente retornam ou imprimem HTML diretamente e são frequentemente chamadas
 * dentro de arquivos de template como single.php, page.php, archive.php, etc.
 * 
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package minimalista
 * @since 1.0.0
 * @author Alexandre Kozoubsky
 */

// ==============================
// Funções de taxonomias
// ==============================

/**
 * Displays a list of post categories in a responsive layout, including subcategories up to any level.
 *
 * @param string $section_title Title for the categories list section.
 * @param string $title_tag Title tag for the section title.
 * @param string $additional_section_classes Additional classes for the section.
 * @param string $additional_item_classes Additional classes for each list item.
 * @param string $page_for_posts_link_text Text for the link to all topics.
 * @param string $page_for_posts_link_classes Classes for the link to all topics.
 * @param string $page_for_posts_link_html_before HTML content before the link to all topics.
 * @param string $page_for_posts_link_html_after HTML content after the link to all topics.
 */
function minimalista_display_categories($section_title = '', $title_tag = 'h3', $additional_section_classes = '', $additional_item_classes = '', $page_for_posts_link_text = '', $page_for_posts_link_classes = '', $page_for_posts_link_html_before = '', $page_for_posts_link_html_after = '')
{

    // Validate and sanitize parameters
    $section_title = sanitize_text_field($section_title);
    $title_tag = in_array($title_tag, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6']) ? $title_tag : 'h3';
    $additional_section_classes = implode(' ', array_map('sanitize_html_class', explode(' ', $additional_section_classes)));
    $additional_item_classes = implode(' ', array_map('sanitize_html_class', explode(' ', $additional_item_classes)));
    $page_for_posts_link_text = sanitize_text_field(__($page_for_posts_link_text, 'minimalista'));
    $page_for_posts_link_classes = implode(' ', array_map('sanitize_html_class', explode(' ', $page_for_posts_link_classes)));
    $page_for_posts_link_html_before = wp_kses_post($page_for_posts_link_html_before);
    $page_for_posts_link_html_after = wp_kses_post($page_for_posts_link_html_after);

    // Query categories
    $args = array(
        'orderby' => 'name',
        'order'   => 'ASC',
        'hide_empty' => true,
        'parent'  => 0
    );

    $categories = get_categories($args);

    // Start section
    echo '<section id="display-categories" class="display-categories ' . esc_attr($additional_section_classes) . '">';

    // Display section title if provided
    if (!empty($section_title)) {
        
       // echo "<{$title_tag}>" . esc_html($section_title) . "</{$title_tag}>";
       echo "<{$title_tag}" . " class='widget-title'" . ">" . esc_html($section_title) . "</{$title_tag}>";
    }

    // Recursive function to display categories and their subcategories
    function display_category_list($categories, $additional_item_classes, $level) {
        echo '<ul class="list-unstyled d-flex flex-column flex-wrap">';
        foreach ($categories as $category) {
            // Display current category
            echo '<li class="display-categories-list-item text-break ' . esc_attr($additional_item_classes) . '">';
            echo str_repeat("&mdash; ", $level); // Visual indication of the level
            echo '<a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a>';

            // Check and display subcategories
            $subcategories = get_categories(array('parent' => $category->term_id, 'hide_empty' => true));
            if (!empty($subcategories)) {
                // Recursive call for each level of subcategories
                display_category_list($subcategories, $additional_item_classes, $level + 1); // Increase level for subcategories
            }
            echo '</li>';
        }
        echo '</ul>';
    }

    // Display categories with the new recursive function
    if (!empty($categories)) {
        display_category_list($categories, $additional_item_classes, 0); // Initialize with level 0
    }

    // Link to the page for posts
    // Verifica se o texto do link está definido e não é vazio antes de exibir o link
    if (!empty($page_for_posts_link_text)) {
        echo $page_for_posts_link_html_before;
        echo '<a href="' . esc_url(get_permalink(get_option('page_for_posts'))) . '" class="all-topics-link ' . esc_attr($page_for_posts_link_classes) . '">' . esc_html( $page_for_posts_link_text ) . '</a>';
        echo $page_for_posts_link_html_after;
    }

    // End section
    echo '</section>';
}

/**
 * Função para exibir subcategorias em múltiplos níveis em um menu horizontal
 * a partir da categoria atual no template content-archive.php do WordPress.
 * @TODO  aproveitar a logica desta funcao na display_categories()
 */
function minimalista_display_subcategories_nav_menu($parent_id = 0) {
    // Somente executa a função se estiver em uma página de categoria
    if (!is_category()) {
        return;
    }

    // Se for a primeira chamada, obtém o ID da categoria atual
    if ($parent_id === 0) {
        $categoria_atual = get_queried_object();
        $parent_id = $categoria_atual->term_id;
    }

    // Busca os termos (subcategorias)
    $subcategorias = get_terms([
        'taxonomy'     => 'category',
        'parent'       => $parent_id,
        'hide_empty'   => false,
    ]);

    // Se não houver subcategorias, retorna
    if (empty($subcategorias)) {
        return;
    }

    // Inicia a exibição do menu
    echo '<nav class="subcategorias-menu">';
    echo '<ul>';

    foreach ($subcategorias as $subcategoria) {
        echo '<li>';
        echo '<a href="' . esc_url(get_category_link($subcategoria->term_id)) . '">' . esc_html($subcategoria->name) . '</a>';

        // Chama a função recursivamente para buscar subníveis
        minimalista_display_subcategories_nav_menu($subcategoria->term_id);

        echo '</li>';
    }

    echo '</ul>';
    echo '</nav>';
}

/**
 * Função para exibir termos de uma taxonomia em múltiplos níveis em um menu horizontal
 * no template content-archive.php do WordPress.
 * 
 * @param int $parent_id ID do termo pai (0 para o termo de nível mais alto).
 * @param string $taxonomy Tipo de taxonomia (padrão 'category').
 */
function minimalista_display_taxonomy_terms_nav_menu_old($parent_id = 0, $taxonomy = 'category') {
    // Verifica se está numa página de taxonomia específica
    if (!is_tax($taxonomy) && !is_category()) {
        return;
    }

    // Define o ID do termo pai, se estiver na primeira chamada
    if ($parent_id === 0) {
        $term_atual = get_queried_object();
        $parent_id = $term_atual->term_id;
    }

    // Busca os termos
    $terms = get_terms([
        'taxonomy'     => $taxonomy,
        'parent'       => $parent_id,
        'hide_empty'   => false,
    ]);

    // Se não houver termos, retorna
    if (empty($terms)) {
        return;
    }

    // Inicia a montagem do menu -  navbar-light bg-light
    echo '<nav class="navbar navbar-expand-lg" data-bs-theme="light">';
    echo '<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScrolldisplayTaxonomy" aria-controls="navbarScrolldisplayTaxonomy" aria-expanded="false" aria-label="Toggle navigation">';
    echo '<span class="navbar-toggler-icon"></span>';
    echo '</button>';
    echo '<div class="collapse navbar-collapse" id="navbarScrolldisplayTaxonomy">';
    echo '<ul class="navbar-nav navbar-nav-scroll">';

    foreach ($terms as $term) {
        echo '<li class="nav-item">';
        echo '<a class="nav-link" href="' . esc_url(get_term_link($term)) . '">' . esc_html($term->name) . '</a>';
        // Chama a função recursivamente para buscar subníveis (opcional)
        minimalista_display_taxonomy_terms_nav_menu_old($term->term_id, $taxonomy);
        echo '</li>';
    }

    echo '</ul>';
    echo '</div>';
    echo '</nav>';
}

/**
 * Função para exibir termos de uma taxonomia em múltiplos níveis em um menu horizontal
 * no template content-archive.php do WordPress.
 * 
 * @param int $parent_id ID do termo pai (0 para o termo de nível mais alto).
 * @param string $taxonomy Tipo de taxonomia (padrão 'category').
 * @param bool $is_subnav Flag para indicar se está renderizando um submenu.
 */
function minimalista_display_taxonomy_terms_nav_menu($parent_id = 0, $taxonomy = 'category', $is_subnav = false) {
    // Verifica se está numa página de taxonomia específica
    if (!is_tax($taxonomy) && !is_category()) {
        return;
    }

    // Define o ID do termo pai, se estiver na primeira chamada
    if ($parent_id === 0) {
        $term_atual = get_queried_object();
        $parent_id = $term_atual->term_id;
    }

    // Busca os termos
    $terms = get_terms([
        'taxonomy'     => $taxonomy,
        'parent'       => $parent_id,
        'hide_empty'   => false,
    ]);

    // Se não houver termos, retorna
    if (empty($terms)) {
        return;
    }

    // Inicia a montagem do menu
    if (!$is_subnav) {
        // Navbar principal
        echo '<nav class="navbar navbar-expand-lg bg-body-secondary border-bottom border-body">';
        echo '<div class="container-fluid">'; 
        echo '<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScrolldisplayTaxonomy" aria-controls="navbarScrolldisplayTaxonomy" aria-expanded="false" aria-label="Toggle navigation">';
        echo '<span class="navbar-toggler-icon"></span>';
        echo '</button>';
        echo '<div class="collapse navbar-collapse" id="navbarScrolldisplayTaxonomy">';
        echo '<ul class="navbar-nav navbar-nav-scroll me-auto mb-2 mb-lg-0">';
    } else {
        // Submenu
        echo '<ul class="dropdown-menu">';
    }

    foreach ($terms as $term) {
        $child_terms = get_terms([
            'taxonomy'     => $taxonomy,
            'parent'       => $term->term_id,
            'hide_empty'   => false,
        ]);

        $has_children = !empty($child_terms);
        $dropdown_class = $has_children ? 'dropdown' : '';
        $dropdown_toggle = $has_children ? 'dropdown-toggle' : '';
        $dropdown_item_class = $is_subnav ? 'dropdown-item' : '';

        echo '<li class="nav-item ' . $dropdown_class . '">';
        echo '<a class="nav-link ' . $dropdown_toggle . $dropdown_item_class . '" href="' . esc_url(get_term_link($term)) . '" ' . ($has_children ? 'data-bs-toggle="dropdown"' : '') . '>' . esc_html($term->name) . '</a>';

        if ($has_children) {
            // Chama a função recursivamente para subníveis
            minimalista_display_taxonomy_terms_nav_menu($term->term_id, $taxonomy, true);
        }

        echo '</li>';
    }

    echo '</ul>';

    if (!$is_subnav) {
        echo '</div>';
        echo '</div>';
        echo '</nav>';
    }
}