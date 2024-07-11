<?php
/**
 * The template part for displaying audio post formats.
 *
 * An audio file or playlist. Could be used for Podcasting.
 *
 * @package LightCMSBootstrap
 * @version 1.0.0
 * @author Alexandre Kozoubsky
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 */

// Validate that the post object exists and post format is 'audio'
if ( is_object( $post ) && has_post_format( 'audio', $post->ID ) ) : ?>

    <div class="format-audio">
        
        <!-- Display the audio content -->
        <div class="audio-content">
            <?php
            // Attempt to get the first <audio> tag from the post content
            $audio_content = get_media_embedded_in_content( apply_filters( 'the_content', get_the_content() ), array( 'audio' ) );

            // If there is an <audio> tag, display it
			/*
            if ( ! empty( $audio_content ) ) {
                echo reset( $audio_content );
			}
			*/
             /* just display the regular content */
             //the_content();
             minimalista_display_post_content();

            ?>
        </div>
        
        <!-- Display post metadata like categories, tags, and comments link -->
		<!--
        <div class="audio-meta">
            <span class="audio-categories"><?php the_category(', '); ?></span>
            <span class="audio-tags"><?php the_tags(); ?></span>
            <span class="audio-comments"><?php comments_popup_link(); ?></span>
        </div>
		-->
    </div>

<?php endif; ?>
