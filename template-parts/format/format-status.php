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
	<div class="">
		<div class="">
			<div class="">
				<?php minimalista_display_author_avatar('',80,'me-3'); ?>
				<span class="post-author fw-bold"><?php echo get_the_author() ?></span>
				<p class="text-muted"><?php minimalista_display_time_since_posted(); ?></p>
			</div>
			<div class="">
				<?php minimalista_display_post_content(); ?>
			</div>
		</div><!-- -->
	</div>
</div>