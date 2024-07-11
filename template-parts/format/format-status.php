<?php
/**
 * Template part for displaying posts in the "Status" format.
 *
 * This template is designed to display short status updates, similar to those found on social media platforms like Twitter.
 *
 * @package minimalista
 * @version 1.0.0
 * @author Alexandre Kozoubsky
 *
 * @link https://developer.wordpress.org/themes/functionality/post-formats/
 * @link https://mdbootstrap.com/docs/standard/extended/news-feed/
 */
?>

<div class="format-status-content">
	<div class="border rounded-3 w-100">
		<div class="d-flex align-items-center p-4">
			<div class="flex-shrink-0">
				<?php minimalista_display_author_avatar('',80,'aligncenter'); ?>
				<span class="post-author fw-bold text-center"><?php echo get_the_author() ?></span>
				<p class="mb-0 text-muted text-center"><?php minimalista_display_time_since_posted(); ?></p>
			</div>
			<div class="flex-grow-1 ms-3">
				<?php minimalista_display_post_content(); ?>
			</div>
		</div><!-- -->
	</div>
</div>