<?php
/**
 * Template part for displaying posts in the Chat format.
 *
 * This template handles the display of posts that are formatted as chats.
 * It is designed to visually distinguish chat content and make it easy to follow the conversation.
 *
 * @package LightCMSBootstrap
 * @version 1.0.0
 * @author Alexandre Kozoubsky
 *
 * @link https://mdbootstrap.com/docs/standard/extended/chat/
 */

?>

<div class="format-chat-content">

	<!-- Chat Icon -->
	<div class="chat-icon">
		<i class="fas fa-comments"></i>
	</div>

	<!-- Chat Content -->
	<div class="">
		<?php
		// Fetch the content and split it into an array of lines
		$chat_lines = explode("\n", get_the_content());

		// Loop through each line and format it
		foreach ($chat_lines as $line) {
			// Separate the speaker and the text
			$chat_line_parts = explode(":", $line, 2);

			// If we have a valid chat line, display it
			if (count($chat_line_parts) > 1) {
				list($speaker, $text) = $chat_line_parts;
				?>
				<div class="card mb-2">
					<div class="chat-line card-body font-monospace">
						<span class="chat-speaker card-title fw-bold mb-0"><?php echo esc_html($speaker); ?>:</span>
						<span class="chat-text card-text"><?php echo esc_html($text); ?></span>
					</div>
				</div>
				<?php
			}
		}
		?>
	</div>

</div>
