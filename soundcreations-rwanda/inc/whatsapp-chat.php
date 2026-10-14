<?php
/**
 * WhatsApp chat widget + mobile quick-action dock (Rwanda).
 *
 * 1. A branded floating chat card on the right. Visitors pick a topic or type
 *    a message and it opens WhatsApp with that message ready to send. Shows
 *    "Online now" during Kigali office hours. No third-party scripts.
 * 2. A mobile dock (Home, Call, Consultation, Menu) that replaces the old
 *    Call / WhatsApp / Consultation bar. WhatsApp now lives in the floating
 *    chat button on the right instead of the bar.
 *
 * Number, prefill and hours come from Sound Creations -> Settings.
 * Turn the chat off with the filter: add_filter( 'scrw_wa_chat_enabled', '__return_false' );
 *
 * @package SoundCreationsRwanda
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WhatsApp number, digits only.
 */
function scrw_wa_number() {
	$raw = function_exists( 'sc_setting' ) ? sc_setting( 'whatsapp', '250783141050' ) : '250783141050';
	return preg_replace( '/\D/', '', (string) $raw );
}

/**
 * Print the floating chat widget.
 */
function scrw_whatsapp_chat() {
	if ( is_admin() || ! apply_filters( 'scrw_wa_chat_enabled', true ) ) {
		return;
	}
	$number = scrw_wa_number();
	if ( '' === $number ) {
		return;
	}
	$company = function_exists( 'sc_setting' ) ? sc_setting( 'company_name', 'Sound Creations Rwanda' ) : 'Sound Creations Rwanda';
	$prefill = function_exists( 'sc_setting' ) ? sc_setting( 'whatsapp_prefill', '' ) : '';
	$logo    = get_template_directory_uri() . '/assets/img/logo-white.webp';
	$topics  = apply_filters(
		'scrw_wa_topics',
		array(
			__( 'I would like a quotation', 'soundcreations-rwanda' ),
			__( 'Sound system installation', 'soundcreations-rwanda' ),
			__( 'Yamaha instruments and pricing', 'soundcreations-rwanda' ),
			__( 'Church or conference sound', 'soundcreations-rwanda' ),
			__( 'Service and repairs', 'soundcreations-rwanda' ),
			__( 'Visiting the Kigali showroom', 'soundcreations-rwanda' ),
		)
	);
	$wa_icon = '<svg viewBox="0 0 32 32" aria-hidden="true" focusable="false"><path fill="currentColor" d="M16 3.2A12.8 12.8 0 0 0 5 22.5L3.2 28.8l6.5-1.7A12.8 12.8 0 1 0 16 3.2zm0 23.3c-2 0-3.9-.5-5.5-1.5l-.4-.2-3.9 1 1-3.8-.2-.4A10.5 10.5 0 1 1 16 26.5zm5.8-7.9c-.3-.2-1.9-.9-2.2-1-.3-.1-.5-.2-.7.2l-1 1.2c-.2.2-.4.2-.7.1-.3-.2-1.4-.5-2.6-1.6-1-.9-1.6-1.9-1.8-2.2-.2-.3 0-.5.1-.7l.5-.6.3-.5c.1-.2 0-.4 0-.6l-1-2.4c-.3-.6-.5-.5-.7-.5h-.6c-.2 0-.6.1-.9.4-.3.3-1.2 1.1-1.2 2.8s1.2 3.2 1.4 3.5c.2.2 2.4 3.6 5.7 5 .8.4 1.4.6 1.9.7.8.2 1.5.2 2.1.1.6-.1 1.9-.8 2.2-1.5.3-.7.3-1.4.2-1.5-.1-.2-.3-.3-.6-.4z"/></svg>';
	?>
	<div class="scrw-wa" data-scrw-wa data-number="<?php echo esc_attr( $number ); ?>" data-prefill="<?php echo esc_attr( $prefill ); ?>" data-tz="Africa/Kigali" data-week="9:00-18:00" data-sat="9:00-13:30" data-open-text="<?php esc_attr_e( 'Online now - usually replies in minutes', 'soundcreations-rwanda' ); ?>" data-closed-text="<?php esc_attr_e( 'Away - we reply when the showroom opens', 'soundcreations-rwanda' ); ?>">
		<div class="scrw-wa__teaser" hidden>
			<button type="button" class="scrw-wa__teaser-close" aria-label="<?php esc_attr_e( 'Dismiss', 'soundcreations-rwanda' ); ?>">&times;</button>
			<p><strong><?php esc_html_e( 'Muraho! Need help choosing gear?', 'soundcreations-rwanda' ); ?></strong> <?php esc_html_e( 'Chat with our Kigali team on WhatsApp for quotes, installs and Yamaha products.', 'soundcreations-rwanda' ); ?></p>
		</div>

		<section class="scrw-wa__panel" id="scrw-wa-panel" role="dialog" aria-modal="false" aria-labelledby="scrw-wa-title" hidden>
			<header class="scrw-wa__head">
				<span class="scrw-wa__avatar"><img src="<?php echo esc_url( $logo ); ?>" alt="" width="44" height="44" loading="lazy" decoding="async"><i class="scrw-wa__presence" aria-hidden="true"></i></span>
				<span class="scrw-wa__who">
					<strong id="scrw-wa-title"><?php echo esc_html( $company ); ?></strong>
					<span class="scrw-wa__status"><?php esc_html_e( 'Replies during showroom hours', 'soundcreations-rwanda' ); ?></span>
				</span>
				<button type="button" class="scrw-wa__close" aria-label="<?php esc_attr_e( 'Close chat', 'soundcreations-rwanda' ); ?>"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6L6 18" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"/></svg></button>
			</header>
			<div class="scrw-wa__body">
				<div class="scrw-wa__bubble">
					<p><?php esc_html_e( 'Hello and welcome to Sound Creations Rwanda.', 'soundcreations-rwanda' ); ?></p>
					<p><?php esc_html_e( 'Tell us about your project or the product you need and a sound specialist will get back to you.', 'soundcreations-rwanda' ); ?></p>
					<span class="scrw-wa__time" aria-hidden="true"></span>
				</div>
				<p class="scrw-wa__label"><?php esc_html_e( 'Pick a topic or type your message', 'soundcreations-rwanda' ); ?></p>
				<div class="scrw-wa__chips">
					<?php foreach ( (array) $topics as $topic ) : ?>
						<button type="button" class="scrw-wa__chip"><?php echo esc_html( $topic ); ?></button>
					<?php endforeach; ?>
				</div>
			</div>
			<form class="scrw-wa__form" action="<?php echo esc_url( 'https://wa.me/' . $number ); ?>" method="get" target="_blank" rel="noopener noreferrer">
				<label class="screen-reader-text" for="scrw-wa-text"><?php esc_html_e( 'Your message', 'soundcreations-rwanda' ); ?></label>
				<textarea id="scrw-wa-text" name="text" rows="1" maxlength="800" placeholder="<?php esc_attr_e( 'Type a message', 'soundcreations-rwanda' ); ?>"></textarea>
				<button type="submit" class="scrw-wa__send" aria-label="<?php esc_attr_e( 'Send on WhatsApp', 'soundcreations-rwanda' ); ?>"><svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M3.4 20.4l17.5-7.5a1 1 0 000-1.8L3.4 3.6a.9.9 0 00-1.3 1l1.8 6.1 9 1.3-9 1.3-1.8 6.1a.9.9 0 001.3 1z"/></svg></button>
			</form>
			<p class="scrw-wa__note"><?php esc_html_e( 'Opens WhatsApp with your message ready to send.', 'soundcreations-rwanda' ); ?></p>
		</section>

		<button type="button" class="scrw-wa__fab" aria-controls="scrw-wa-panel" aria-expanded="false" aria-label="<?php esc_attr_e( 'Chat with us on WhatsApp', 'soundcreations-rwanda' ); ?>">
			<span class="scrw-wa__fab-icon"><?php echo $wa_icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- static SVG. ?></span>
			<span class="scrw-wa__fab-label"><?php esc_html_e( 'Chat with us', 'soundcreations-rwanda' ); ?></span>
			<span class="scrw-wa__fab-dot" aria-hidden="true"></span>
		</button>
	</div>
	<?php
}
add_action( 'wp_footer', 'scrw_whatsapp_chat', 6 );

/**
 * Mobile quick-action dock: Home, Call, Consultation, Menu.
 */
function scrw_mobile_dock() {
	if ( is_admin() ) {
		return;
	}
	$tel     = function_exists( 'sc_setting' ) ? sc_setting( 'phone_link' ) : '';
	$is_home = is_front_page();
	$is_cons = is_page( 'request-a-consultation' );
	?>
	<nav class="scrw-dock" aria-label="<?php esc_attr_e( 'Quick actions', 'soundcreations-rwanda' ); ?>">
		<a class="scrw-dock__item<?php echo $is_home ? ' is-current' : ''; ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>"<?php echo $is_home ? ' aria-current="page"' : ''; ?>>
			<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 10.5 12 3l9 7.5V20a1 1 0 0 1-1 1h-5v-6h-6v6H4a1 1 0 0 1-1-1z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>
			<span><?php esc_html_e( 'Home', 'soundcreations-rwanda' ); ?></span>
		</a>
		<?php if ( strlen( (string) $tel ) > 0 ) : ?>
		<a class="scrw-dock__item" href="tel:<?php echo esc_attr( $tel ); ?>">
			<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2 4.2 2 2 0 0 1 4 2h3a2 2 0 0 1 2 1.7c.1.9.3 1.8.6 2.6a2 2 0 0 1-.5 2.1L7.6 9.8a16 16 0 0 0 6 6l1.4-1.4a2 2 0 0 1 2.1-.5c.8.3 1.7.5 2.6.6A2 2 0 0 1 22 16.9z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
			<span><?php esc_html_e( 'Call', 'soundcreations-rwanda' ); ?></span>
		</a>
		<?php endif; ?>
		<a class="scrw-dock__item scrw-dock__item--cta<?php echo $is_cons ? ' is-current' : ''; ?>" href="<?php echo esc_url( home_url( '/request-a-consultation/' ) ); ?>">
			<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M8 2v3M16 2v3M3.5 9h17M5 4h14a1.5 1.5 0 0 1 1.5 1.5V20A1.5 1.5 0 0 1 19 21.5H5A1.5 1.5 0 0 1 3.5 20V5.5A1.5 1.5 0 0 1 5 4zm3 9.5 2.5 2.5L16 11" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
			<span><?php esc_html_e( 'Consultation', 'soundcreations-rwanda' ); ?></span>
		</a>
		<button type="button" class="scrw-dock__item scrw-dock__item--menu" data-sc-menu-toggle aria-controls="sc-primary-nav" aria-expanded="false">
			<span class="scrw-dock__burger" aria-hidden="true"><i></i><i></i><i></i></span>
			<span class="scrw-dock__menu-open"><?php esc_html_e( 'Menu', 'soundcreations-rwanda' ); ?></span>
			<span class="scrw-dock__menu-close"><?php esc_html_e( 'Close', 'soundcreations-rwanda' ); ?></span>
		</button>
	</nav>
	<?php
}
add_action( 'wp_footer', 'scrw_mobile_dock', 7 );
