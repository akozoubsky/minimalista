<?php
/**
 * Classe Bootstrap_Nav_Walker
 * Estende Walker_Nav_Menu para implementar suporte ao estilo Bootstrap para menus do WordPress.
 */
class Bootstrap_Nav_Walker extends Walker_Nav_Menu {

    // start_lvl() e end_lvl(): Estes métodos controlam a abertura e fechamento de submenus.

    /**
     * Inicia o nível do submenu.
     * 
     * @param string   $output Armazena a saída do menu.
     * @param int      $depth  Profundidade atual do menu.
     * @param stdClass $args   Argumentos do menu.
     */
    public function start_lvl( &$output, $depth = 0, $args = null ) {
        $indent = str_repeat("\t", $depth);
        //$output .= "$indent<ul class=\"dropdown-menu\" aria-labelledby=\"navbarDropdown\">\n";
        $output .= "$indent<ul class=\"dropdown-menu\">\n";
    }

    /**
     * Finaliza o nível do submenu.
     * 
     * @param string   $output Armazena a saída do menu.
     * @param int      $depth  Profundidade atual do menu.
     * @param stdClass $args   Argumentos do menu.
     */
    public function end_lvl( &$output, $depth = 0, $args = null ) {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</ul>\n";
    }

    /**
     * Inicia um item do menu.
     * 
     * @param string   $output Armazena a saída do menu.
     * @param WP_Post  $item   Item atual do menu.
     * @param int      $depth  Profundidade atual do item no menu.
     * @param stdClass $args   Argumentos do menu.
     * @param int      $id     ID do item do menu.
     */
	public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        $indent = ($depth) ? str_repeat("\t", $depth) : '';

        // Adiciona a classe 'dropdown' aos itens de menu que têm filhos.
        $li_classes = array('nav-item');
        if ($args->walker->has_children) {
            $li_classes[] = 'dropdown';
        }

        if ($item->current) {
            $li_classes[] = 'active';
        }

        $classes = implode(' ', apply_filters('nav_menu_css_class', array_filter($li_classes), $item, $args));
        $output .= $indent . '<li class="' . esc_attr($classes) . '">';

        // Monta os atributos do link do menu.
        $atts = array(
            'title'  => !empty($item->attr_title) ? $item->attr_title : '',
            'target' => !empty($item->target)     ? $item->target     : '',
            'rel'    => !empty($item->xfn)        ? $item->xfn        : '',
            'href'   => !empty($item->url)        ? $item->url        : '',
            'class'  => 'nav-link',
        );

        // Se o item do menu tem filhos, adicione as classes 'dropdown-toggle' e os atributos necessários.
		if ($args->walker->has_children) {
            $atts['class']        .= ' dropdown-toggle';
            $atts['id']            = 'menu-item-dropdown-' . $item->ID;
            $atts['data-bs-toggle'] = 'dropdown';
            $atts['aria-haspopup'] = 'true';
            $atts['aria-expanded'] = 'false';
        } else if ($depth > 0) {
            // Se um item do menu não é um item com filhos (não é um 'dropdown') 
            // e está em um nível de profundidade maior que 0 (ou seja, é um item de submenu), 
            // então ele receberá a classe `'dropdown-item'`.
            $atts['class'] .= ' dropdown-item';
        }

        $atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args);

        // Concatena os atributos para a tag 'a'.
        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (!empty($value)) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        // Construção do HTML do item do menu.
        $item_output = $args->before;
        $item_output .= '<a' . $attributes . '>';
        $item_output .= $args->link_before . apply_filters('the_title', $item->title, $item->ID) . $args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        // Adiciona o item do menu à saída.
        $output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
    }

    /**
     * Finaliza um item do menu.
     * 
     * @param string   $output Armazena a saída do menu.
     * @param WP_Post  $item   Item atual do menu.
     * @param int      $depth  Profundidade atual do item no menu.
     * @param stdClass $args   Argumentos do menu.
     */
    public function end_el( &$output, $item, $depth = 0, $args = null ) {
        $output .= "</li>\n";
    }

    /**
     * Verifica e exibe o elemento do menu.
     * 
     * @param WP_Post  $element           Elemento do menu a ser exibido.
     * @param array    $children_elements Elementos filhos do item atual.
     * @param int      $max_depth         Profundidade máxima do menu.
     * @param int      $depth             Profundidade atual do item no menu.
     * @param stdClass $args              Argumentos do menu.
     * @param string   $output            Armazena a saída do menu.
     */
    public function display_element($element, &$children_elements, $max_depth, $depth, $args, &$output) {
        parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
    }
}
?>