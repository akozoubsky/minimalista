<?php
/**
 * Template part for displaying video post format.
 *
 * A single video or video playlist. The first <video /> tag or object/embed in the post content could be considered the video.
 * Alternatively, if the post consists only of a URL, that will be the video URL.
 * May also contain the video as an attachment to the post, if video support is enabled on the blog (like via a plugin).
 *
 * @package Minimalista
 * @version 1.0.0
 * @author Alexandre Kozoubsky
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 * @TODO
 */
?>

<!-- Content for video format. Usually contains a single video or video playlist. -->
<div class="format-video-content">
    <?php
    // Retrieve and display the video content.
    $content = apply_filters('the_content', get_the_content());
    $video = get_media_embedded_in_content($content, array('video', 'object', 'embed', 'iframe'));
    ?>

    <?php if (!empty($video)): ?>
        <div class="video-wrapper">
            <?php echo $video[0]; ?>
        </div>
    <?php else: ?>
        <!-- <p>No video found for this post.</p> -->
		<p>Nenhum vídeo encontrado para esta postagem</p>
    <?php endif; ?>
</div>

