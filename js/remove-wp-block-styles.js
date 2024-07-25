/**
 * File remove-wp-block-styles.js
 *
 * @package minimalista
 * @since 1.0.0
 * @author Alexandre Kozoubsky
 */

document.addEventListener("DOMContentLoaded", function () {
    var styles = document.getElementsByTagName('style');
    var regex = /\.wp-block-[a-zA-Z0-9-_]+/g; // Expressão regular para encontrar estilos que começam com "wp-block-"

    for (var i = 0; i < styles.length; i++) {
        if (regex.test(styles[i].innerHTML)) {
            styles[i].parentNode.removeChild(styles[i]);
            i--; // Decrementar o índice para compensar a remoção do elemento
        }
    }
});
