<?php
/**
 * "Experience a Scensara Prompt" interactive section.
 *
 * @package Scensara
 */
$prompts = array(
	array( 'Chapter I · Noise', 'What mental clutter are you ready to release today?', 'Name three thoughts that keep circling. Which one deserves your attention, and which can wait?' ),
	array( 'Chapter II · Priorities', 'If you could only complete one outcome this month, what would make everything else easier?', 'Write it down in a single sentence, then list what you will say no to in order to protect it.' ),
	array( 'Chapter III · Boundaries', 'Which commitment are you keeping solely out of habit?', 'What would change if you paused it for seven days? Write the first honest answer that comes to mind.' ),
);
?>
<section class="section section-sand" id="prompt-preview">
	<div class="container">
		<div class="section-header">
			<span class="eyebrow"><?php esc_html_e( 'Try It Now', 'scensara' ); ?></span>
			<h2><?php esc_html_e( 'Experience a Scensara Prompt', 'scensara' ); ?></h2>
			<p><?php esc_html_e( 'A glimpse inside the journal. Choose a chapter and write freely. Nothing you type here is saved or sent.', 'scensara' ); ?></p>
		</div>
		<div class="prompt-box">
			<div class="prompt-tabs" role="tablist">
				<?php foreach ( $prompts as $i => $p ) : ?>
					<button type="button" role="tab" class="prompt-tab<?php echo 0 === $i ? ' active' : ''; ?>" data-prompt="<?php echo esc_attr( $i ); ?>" aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>"><?php echo esc_html( $p[0] ); ?></button>
				<?php endforeach; ?>
			</div>
			<?php foreach ( $prompts as $i => $p ) : ?>
				<div class="prompt-panel" data-prompt-panel="<?php echo esc_attr( $i ); ?>"<?php echo 0 === $i ? '' : ' hidden'; ?>>
					<p class="prompt-question">&ldquo;<?php echo esc_html( $p[1] ); ?>&rdquo;</p>
					<p class="prompt-sub"><?php echo esc_html( $p[2] ); ?></p>
				</div>
			<?php endforeach; ?>
			<label class="screen-reader-text" for="prompt-answer"><?php esc_html_e( 'Your private reflection', 'scensara' ); ?></label>
			<textarea id="prompt-answer" class="prompt-textarea" rows="5" placeholder="<?php esc_attr_e( 'Write your private reflection here…', 'scensara' ); ?>"></textarea>
		</div>
	</div>
</section>
