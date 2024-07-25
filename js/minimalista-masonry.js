/**
 * File minimalista-masonry.js
 *
 * @package minimalista
 * @since 1.0.0
 * @author Alexandre Kozoubsky
 */

document.addEventListener('DOMContentLoaded', function () {
    var elem = document.querySelector('.gallery');
    if (elem) {
        var msnry = new Masonry(elem, {
            itemSelector: '.gallery-item',
            columnWidth: '.gallery-item',
            percentPosition: true
        });
    }
});
