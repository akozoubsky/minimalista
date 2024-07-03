<?php
/**
 * The template for displaying comments and the comment form.
 * Incorporates Bootstrap 5.3 styles for layout and components.
 * 
 * @package LightCMSBootstrap
 * @version 1.0.0
 * @author Alexandre Kozoubsky
 */

// Exit if accessed directly, if password is required, or if comments are closed with none present.
if ( post_password_required() || ( ! have_comments() && ! comments_open() ) ) {
    return;
}
?>

<div id="comments" class="comments-area mt-5">

    <!-- Section header for the comments -->
    <h2 class="comments-title mb-3">
        Comentários
    </h2>

    <!-- Comments List -->
    <?php if ( have_comments() ) : ?>
        <ol class="comment-list mb-5">
            <?php
			wp_list_comments(
				array(
					'style'       => 'ol', // 'div' ou 'ol' dependendo da sua marcação desejada
					'short_ping'  => true,
					'callback'    => 'bootstrap_comment_callback', // Nossa função de callback personalizada
					'avatar_size' => 50,
				)
			);
            ?>
        </ol>

        <!-- Comments Pagination -->
        <?php the_comments_pagination(); ?>
    <?php endif; ?>

    <!-- Comments Form -->
    <?php if ( comments_open() ) : ?>
        <div class="comment-form-wrapper">
            <?php validate_comment_form(array(
                'class_form'           => 'needs-validation', // Bootstrap class
                'class_submit'         => 'btn btn-primary', // Bootstrap's button class
                'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title">',
                'title_reply_after'    => '</h3>',
            )); ?>
        </div>
    <?php endif; ?>

</div><!-- #comments -->

