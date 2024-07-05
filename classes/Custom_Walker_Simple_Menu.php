<?php
class Custom_Walker_Simple_Menu extends Walker_Nav_Menu {
  public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

    $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
    $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
    $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
    $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

    // Criar elemento <a>
    $item_output = $args->before;
    $item_output .= '<a'. $attributes .'>' . $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after . '</a>';
    $item_output .= $args->after;

    // Concatenar ao output final
    $output .= $item_output;
  }
}
?>

