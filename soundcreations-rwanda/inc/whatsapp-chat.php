<?php
/**
 * WhatsApp chat card (Rwanda), ported from soundcreationsltd.com so both
 * sites look and behave the same.
 *
 * Desktop: a "Chat with us" pill bottom-right that opens a branded chat card.
 * Phones: a round WhatsApp button floats on the right above the bottom tab
 * bar (footer.php) and the card opens as a bottom sheet.
 * Online status follows the Kigali showroom hours. Without JavaScript the
 * button is still a working wa.me link. No third-party scripts.
 *
 * @package SoundCreationsRwanda
 */

if ( \! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Kigali showroom hours, minutes from midnight per weekday.
 * Mon-Fri 9:00-18:00, Sat 9:00-13:30, Sun closed.
 *
 * @return array<string, array{0:int,1:int}>
 */
function scrw_wa_hours() {
	$out = array();
	foreach ( array( 'Mon', 'Tue', 'Wed', 'Thu', 'Fri' ) as $d ) {
		$out[ $d ] = array( 540, 1080 );
	}
	$out['Sat'] = array( 540, 810 );
	return apply_filters( 'scrw_wa_hours', $out );
}

/**
 * Print the chat widget in the footer.
 */
function scrw_whatsapp_chat() {
	if ( is_admin() || function_exists( 'sc_whatsapp_url' ) === false ) {
		return;
	}
	$sc_base = (string) sc_whatsapp_url();
	$sc_num  = preg_replace( '/\D/', '', $sc_base );
	if ( '' === (string) $sc_num ) {
		return;
	}
	$sc_greet  = (string) sc_setting( 'whatsapp_prefill', 'Hello Sound Creations Rwanda, I would like to enquire about your services.' );
	$sc_nojs   = 'https://wa.me/' . $sc_num . '?text=' . rawurlencode( $sc_greet );
	$sc_avatar = get_template_directory_uri() . '/assets/img/logo-white.webp';
	$sc_topics = apply_filters(
		'scrw_wa_topics',
		array(
			__( 'I would like a quotation', 'soundcreations-rwanda' ),
			__( 'Sound system design and installation', 'soundcreations-rwanda' ),
			__( 'Yamaha instruments and pricing', 'soundcreations-rwanda' ),
			__( 'Church or conference room sound', 'soundcreations-rwanda' ),
			__( 'Service and repairs', 'soundcreations-rwanda' ),
			__( 'Visiting the Kigali showroom', 'soundcreations-rwanda' ),
		)
	);
	$sc_icon = '<svg viewBox="0 0 32 32" aria-hidden="true" focusable="false"><path fill="currentColor" d="M16 3.2A12.8 12.8 0 0 0 5 22.5L3.2 28.8l6.5-1.7A12.8 12.8 0 1 0 16 3.2zm0 23.3c-2 0-3.9-.5-5.5-1.5l-.4-.2-3.9 1 1-3.8-.2-.4A10.5 10.5 0 1 1 16 26.5zm5.8-7.9c-.3-.2-1.9-.9-2.2-1-.3-.1-.5-.2-.7.2l-1 1.2c-.2.2-.4.2-.7.1-.3-.2-1.4-.5-2.6-1.6-1-.9-1.6-1.9-1.8-2.2-.2-.3 0-.5.1-.7l.5-.6.3-.5c.1-.2 0-.4 0-.6l-1-2.4c-.3-.6-.5-.5-.7-.5h-.6c-.2 0-.6.1-.9.4-.3.3-1.2 1.1-1.2 2.8s1.2 3.2 1.4 3.5c.2.2 2.4 3.6 5.7 5 .8.4 1.4.6 1.9.7.8.2 1.5.2 2.1.1.6-.1 1.9-.8 2.2-1.5.3-.7.3-1.4.2-1.5-.1-.2-.3-.3-.6-.4z"/></svg>';
	?>
	<div class="sc-wa" data-sc-wa data-number="<?php echo esc_attr( $sc_num ); ?>" data-hours="<?php echo esc_attr( wp_json_encode( scrw_wa_hours() ) ); ?>" data-open-text="<?php esc_attr_e( 'Online now, usually replies within minutes', 'soundcreations-rwanda' ); ?>" data-closed-text="<?php esc_attr_e( 'Leave a message, we reply when the showroom opens', 'soundcreations-rwanda' ); ?>">
		<div class="sc-wa__teaser" hidden>
			<button type="button" class="sc-wa__teaser-close" aria-label="<?php esc_attr_e( 'Dismiss', 'soundcreations-rwanda' ); ?>">&times;</button>
			<p><strong><?php esc_html_e( 'Muraho\! Planning a project?', 'soundcreations-rwanda' ); ?></strong> <?php esc_html_e( 'Chat with our Kigali team on WhatsApp for quotes, installs and Yamaha products.', 'soundcreations-rwanda' ); ?></p>
		</div>
		<div class="sc-wa__scrim" hidden></div>
		<section class="sc-wa__panel" id="sc-wa-panel" role="dialog" aria-modal="false" aria-labelledby="sc-wa-title" hidden>
			<header class="sc-wa__head">
				<span class="sc-wa__avatar"><img src="<?php echo esc_url( $sc_avatar ); ?>" alt="" width="44" height="44" loading="lazy" decoding="async"><i class="sc-wa__presence" aria-hidden="true"></i></span>
				<span class="sc-wa__who">
					<strong id="sc-wa-title"><?php esc_html_e( 'Sound Creations Rwanda', 'soundcreations-rwanda' ); ?></strong>
					<span class="sc-wa__status"><?php esc_html_e( 'Usually replies during showroom hours', 'soundcreations-rwanda' ); ?></span>
				</span>
				<button type="button" class="sc-wa__close" aria-label="<?php esc_attr_e( 'Close chat', 'soundcreations-rwanda' ); ?>"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/></svg></button>
			</header>
			<div class="sc-wa__body">
				<div class="sc-wa__bubble">
					<p><?php esc_html_e( 'Hello and welcome to Sound Creations Rwanda.', 'soundcreations-rwanda' ); ?></p>
					<p><?php esc_html_e( 'Tell us about your space or the equipment you need, and our team will help.', 'soundcreations-rwanda' ); ?></p>
					<span class="sc-wa__time" aria-hidden="true"></span>
				</div>
				<p class="sc-wa__label"><?php esc_html_e( 'Pick a topic or type your own message', 'soundcreations-rwanda' ); ?></p>
				<div class="sc-wa__chips">
					<?php foreach ( $sc_topics as $sc_topic ) : ?>
						<button type="button" class="sc-wa__chip"><?php echo esc_html( $sc_topic ); ?></button>
					<?php endforeach; ?>
				</div>
			</div>
			<form class="sc-wa__form" action="<?php echo esc_url( 'https://wa.me/' . $sc_num ); ?>" method="get" target="_blank" rel="noopener noreferrer">
				<label class="screen-reader-text" for="sc-wa-text"><?php esc_html_e( 'Your message', 'soundcreations-rwanda' ); ?></label>
				<textarea id="sc-wa-text" name="text" rows="1" maxlength="800" placeholder="<?php esc_attr_e( 'Type a message', 'soundcreations-rwanda' ); ?>"></textarea>
				<button type="submit" class="sc-wa__send" aria-label="<?php esc_attr_e( 'Send on WhatsApp', 'soundcreations-rwanda' ); ?>"><svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M3.4 20.4l17.5-7.5a1 1 0 000-1.8L3.4 3.6a.9.9 0 00-1.3 1l1.8 6.1 9 1.3-9 1.3-1.8 6.1a.9.9 0 001.3 1z"/></svg></button>
			</form>
			<p class="sc-wa__note"><?php esc_html_e( 'Opens WhatsApp with your message ready to send.', 'soundcreations-rwanda' ); ?></p>
		</section>
		<a class="sc-wa__fab" href="<?php echo esc_url( $sc_nojs ); ?>" target="_blank" rel="noopener noreferrer nofollow" aria-controls="sc-wa-panel" aria-expanded="false" aria-label="<?php esc_attr_e( 'Chat with us on WhatsApp', 'soundcreations-rwanda' ); ?>">
			<span class="sc-wa__fab-icon"><?php echo $sc_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></span>
			<span class="sc-wa__fab-label"><?php esc_html_e( 'Chat with us', 'soundcreations-rwanda' ); ?></span>
		</a>
	</div>
	<?php
}
add_action( 'wp_footer', 'scrw_whatsapp_chat', 6 );
