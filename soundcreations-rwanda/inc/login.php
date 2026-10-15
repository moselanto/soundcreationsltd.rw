<?php
/**
 * Branded sign-in page (wp-login.php) for Sound Creations Rwanda.
 *
 * Split layout: a dark brand panel on the left (headline, what staff can do,
 * help contacts) and a clean sign-in card on the right with the logo,
 * "Welcome back", the form, and a security note underneath.
 * Login, lost-password and reset screens all share the look.
 * The pop-up "session expired" login inside wp-admin is left plain.
 *
 * @package soundcreations-rwanda
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** True for the small re-auth pop-up inside wp-admin. */
function scrw_login_is_interim() {
	return ! empty( $GLOBALS['interim_login'] ) || isset( $_REQUEST['interim-login'] ); // phpcs:ignore WordPress.Security.NonceVerification
}

/** Current wp-login action (login, lostpassword, rp, ...). */
function scrw_login_action() {
	$action = isset( $_REQUEST['action'] ) ? sanitize_key( wp_unslash( $_REQUEST['action'] ) ) : 'login'; // phpcs:ignore WordPress.Security.NonceVerification
	return '' === $action ? 'login' : $action;
}

/* Styles. */
add_action(
	'login_enqueue_scripts',
	function () {
		$rel  = 'assets/css/scrw-login.css';
		$path = get_stylesheet_directory() . '/' . $rel;
		$ver  = file_exists( $path ) ? (string) filemtime( $path ) : SCRW_VERSION;
		wp_enqueue_style( 'scrw-login', get_stylesheet_directory_uri() . '/' . $rel, array( 'login' ), $ver );

		$logo = get_stylesheet_directory_uri() . '/assets/img/login-logo.png';
		$bg   = get_stylesheet_directory_uri() . '/assets/img/about-hero-rw.webp';
		wp_add_inline_style(
			'scrw-login',
			':root{--scrw-login-logo:url("' . esc_url_raw( $logo ) . '");--scrw-login-bg:url("' . esc_url_raw( $bg ) . '");}'
		);
	}
);

/* Logo links to the website, not wordpress.org. */
add_filter(
	'login_headerurl',
	function () {
		return home_url( '/' );
	}
);
add_filter(
	'login_headertext',
	function () {
		return get_bloginfo( 'name' );
	}
);

/* Body class so the CSS only styles the full page, not the wp-admin pop-up. */
add_filter(
	'login_body_class',
	function ( $classes ) {
		if ( ! scrw_login_is_interim() ) {
			$classes[] = 'scrw-login';
		}
		return $classes;
	}
);

/* Left brand panel. */
add_action(
	'login_header',
	function () {
		if ( scrw_login_is_interim() ) {
			return;
		}
		$tick = '<svg viewBox="0 0 20 20" width="18" height="18" aria-hidden="true"><circle cx="10" cy="10" r="10" fill="currentColor" opacity=".18"/><path d="M6 10.4l2.6 2.6L14.2 7.4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>';
		?>
		<aside class="scrw-login-brand" aria-label="<?php esc_attr_e( 'About this sign-in page', 'soundcreations-rwanda' ); ?>">
			<div class="scrw-login-brand__inner">
				<a class="scrw-login-brand__logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
					<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/img/login-logo.png' ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" width="220" height="77">
				</a>
				<div class="scrw-login-brand__body">
					<span class="scrw-login-brand__eyebrow"><?php esc_html_e( 'Staff and team', 'soundcreations-rwanda' ); ?></span>
					<h2 class="scrw-login-brand__title"><?php esc_html_e( 'Every great sound starts with the work you do here.', 'soundcreations-rwanda' ); ?></h2>
					<p class="scrw-login-brand__lead"><?php esc_html_e( 'Sign in to look after the Sound Creations Rwanda website, Kigali.', 'soundcreations-rwanda' ); ?></p>
					<ul class="scrw-login-brand__list">
						<li><?php echo $tick; // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php esc_html_e( 'Update pages, solutions and project stories', 'soundcreations-rwanda' ); ?></span></li>
						<li><?php echo $tick; // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php esc_html_e( 'Manage products, brands and photos', 'soundcreations-rwanda' ); ?></span></li>
						<li><?php echo $tick; // phpcs:ignore WordPress.Security.EscapeOutput ?><span><?php esc_html_e( 'Answer enquiries from clients and partners', 'soundcreations-rwanda' ); ?></span></li>
					</ul>
				</div>
				<p class="scrw-login-brand__help">
					<?php esc_html_e( 'Need access or locked out?', 'soundcreations-rwanda' ); ?>
					<a href="tel:+250783141050">+250 783 141 050</a>
					<a href="mailto:info@soundcreationsltd.com">info@soundcreationsltd.com</a>
				</p>
			</div>
		</aside>
		<?php
	}
);

/* "Welcome back" heading inside the card (sign-in screen only). */
add_filter(
	'login_message',
	function ( $message ) {
		if ( scrw_login_is_interim() || 'login' !== scrw_login_action() ) {
			return $message;
		}
		$intro  = '<div class="scrw-login-welcome">';
		$intro .= '<h2>' . esc_html__( 'Welcome back', 'soundcreations-rwanda' ) . '</h2>';
		$intro .= '<p>' . esc_html__( 'Sign in to manage the Sound Creations Rwanda website.', 'soundcreations-rwanda' ) . '</p>';
		$intro .= '</div>';
		return $intro . $message;
	}
);

/* Security note and copyright under the card. */
add_action(
	'login_footer',
	function () {
		if ( scrw_login_is_interim() ) {
			return;
		}
		?>
		<div class="scrw-login-foot">
			<p class="scrw-login-foot__secure">
				<svg viewBox="0 0 20 20" width="14" height="14" aria-hidden="true"><path d="M5 9V6.5a5 5 0 0110 0V9" fill="none" stroke="currentColor" stroke-width="1.8"/><rect x="3.5" y="9" width="13" height="9" rx="2" fill="currentColor"/></svg>
				<?php esc_html_e( 'Secure connection. Repeated failed attempts are paused for 15 minutes.', 'soundcreations-rwanda' ); ?>
			</p>
			<p class="scrw-login-foot__copy">&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> Sound Creations Ltd, Kigali, Rwanda</p>
		</div>
		<?php
	}
);

/* Hide the language dropdown on the sign-in page. */
add_filter( 'login_display_language_dropdown', '__return_false' );
