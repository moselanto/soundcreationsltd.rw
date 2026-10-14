<?php
/**
 * Footer template. Three editable columns:
 *   1. About + social   2. Open Hours   3. Contact (head office).
 * Explore and Solutions were removed on 2026-09-25 (owner request) and Open
 * Hours was promoted out of Contact to take the slot Solutions held.
 * Every value is editable in wp-admin: Sound Creations -> Settings (Footer section).
 *
 * Child copy of the parent footer.php. Only change: the old floating WhatsApp
 * link and three-button bar are replaced by the .com tab bar below and the
 * WhatsApp chat card (inc/whatsapp-chat.php).
 *
 * @package SoundCreationsRwanda
 */

if ( defined( 'ABSPATH' ) === false ) {
	exit;
}
$sc_addr    = sc_split_lines( sc_setting( 'footer_address' ), 1 );
$sc_hours   = sc_split_lines( sc_setting( 'footer_hours' ), 1 );
$sc_phone   = sc_setting( 'phone' );
$sc_phone_l = sc_setting( 'phone_link' );
$sc_email   = sc_setting( 'email' );
?>
</main>

<footer class="sc-footer">
	<div class="sc-container">
		<div class="sc-footer__grid">

			<div class="sc-foot-about">
				<div class="sc-foot-logo">
					<?php
					echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="sc-logo" rel="home">'
					. '<img class="sc-logo__img sc-logo__img--dark" src="' . esc_url( SC_THEME_URI . '/assets/img/logo-white.webp' ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" width="309" height="115">'
					. '<img class="sc-logo__img sc-logo__img--light" src="' . esc_url( SC_THEME_URI . '/assets/img/logo-color.webp' ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" width="309" height="115">'
					. '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- first-party logo markup.
					?>
				</div>
				<p class="sc-foot-tagline"><?php echo esc_html( sc_setting( 'slogan', 'If it sounds good, it’s Sound Creations.' ) ); ?></p>
				<div class="sc-foot-soc"><?php sc_all_social(); ?></div>
			</div>

			<div class="sc-foot-nav">

			<?php if ( count( $sc_hours ) > 0 ) : ?>
			<div class="sc-foot-col sc-foot-col--hours">
				<h2><?php echo esc_html( sc_setting( 'footer_hours_label', 'Open Hours' ) ); ?></h2>
				<ul>
					<?php
					foreach ( $sc_hours as $sc_row ) {
						if ( '' === $sc_row[0] ) {
							continue;
						}
						echo '<li>' . esc_html( $sc_row[0] ) . '</li>';
					}
					?>
				</ul>
			</div>
			<?php endif; ?>

			<div class="sc-foot-contact">
				<h2><?php esc_html_e( 'Contact', 'soundcreations' ); ?></h2>
				<address class="sc-foot-addr">
					<?php
					$sc_map_url = sc_setting( 'map_url', 'https://share.google/K15Qu2ngP7wlNnd0Y' );
					$sc_has_map = strlen( (string) $sc_map_url ) > 0;
					if ( $sc_has_map ) {
						echo '<a class="sc-foot-addr__link" href="' . esc_url( $sc_map_url ) . '" target="_blank" rel="noopener noreferrer">';
					}
					foreach ( $sc_addr as $sc_row ) {
						if ( '' === $sc_row[0] ) {
							continue;
						}
						// Phones print on their own lines below the address, so skip any
						// address line that is only phone numbers (stops the number showing twice).
						if ( preg_match( '/^[\s\d+()|\/.-]+$/', $sc_row[0] ) && preg_match( '/\d{6,}/', preg_replace( '/\D/', '', $sc_row[0] ) ) ) {
							continue;
						}
						echo '<span>' . esc_html( $sc_row[0] ) . '</span>';
					}
					if ( $sc_has_map ) {
						echo '</a>';
					}
					?>
				</address>
				<?php if ( $sc_phone ) : ?>
					<a href="tel:<?php echo esc_attr( $sc_phone_l ); ?>"><?php echo esc_html( $sc_phone ); ?></a>
				<?php endif; ?>
				<?php if ( sc_setting( 'phone2' ) ) : ?>
					<a href="tel:<?php echo esc_attr( sc_setting( 'phone2_link' ) ); ?>"><?php echo esc_html( sc_setting( 'phone2' ) ); ?></a>
				<?php endif; ?>
				<?php if ( $sc_email ) : ?>
					<a href="mailto:<?php echo esc_attr( $sc_email ); ?>"><?php echo esc_html( $sc_email ); ?></a>
				<?php endif; ?>
				<?php if ( sc_setting( 'email2' ) ) : ?>
					<a href="mailto:<?php echo esc_attr( sc_setting( 'email2' ) ); ?>"><?php echo esc_html( sc_setting( 'email2' ) ); ?></a>
				<?php endif; ?>
			</div>

			</div>
		</div>

		<div class="sc-footer__bottom">
			<div>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html( sc_setting( 'company_name' ) ); ?>. <?php esc_html_e( 'All rights reserved.', 'soundcreations' ); ?></div>
			<div class="sc-footer__legal">
				<a href="<?php echo esc_url( home_url( '/privacy-policy/' ) ); ?>"><?php esc_html_e( 'Privacy Policy', 'soundcreations' ); ?></a>
				<a href="<?php echo esc_url( home_url( '/terms/' ) ); ?>"><?php esc_html_e( 'Terms & Conditions', 'soundcreations' ); ?></a>
			</div>
		</div>
	</div>
</footer>

<?php
/* WhatsApp chat card: see inc/whatsapp-chat.php (printed via wp_footer). */
?>
<?php
/*
 * Mobile bottom navigation, same as soundcreationsltd.com (v0.11.x), Rwanda 1.9.2.
 * Phones and small tablets only. Five big, labelled tabs so visitors who are
 * not used to hamburger menus can always get Home, see Solutions, ask for a
 * consultation, call, or open the full menu. WhatsApp is the round floating
 * button on the right (inc/whatsapp-chat.php), so it is not repeated here.
 */
$sc_bar_tel     = (string) sc_setting( 'phone_link' );
$sc_tab_home    = is_front_page();
$sc_tab_sol     = is_post_type_archive( 'sc_solution' ) || is_singular( 'sc_solution' ) || is_page( 'solutions' );
$sc_tab_consult = is_page( array( 'request-a-consultation', 'contact' ) );
?>
<nav class="sc-tabbar" aria-label="<?php esc_attr_e( 'Quick navigation', 'soundcreations-rwanda' ); ?>">
	<a class="sc-tabbar__item<?php echo $sc_tab_home ? ' is-current' : ''; ?>" href="<?php echo esc_url( home_url( '/' ) ); ?>"<?php echo $sc_tab_home ? ' aria-current="page"' : ''; ?>>
		<span class="sc-tabbar__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M3 10.5 12 3l9 7.5"/><path d="M5.5 9v11h4.5v-6h4v6h4.5V9"/></svg></span>
		<span class="sc-tabbar__label"><?php esc_html_e( 'Home', 'soundcreations-rwanda' ); ?></span>
	</a>
	<a class="sc-tabbar__item<?php echo $sc_tab_sol ? ' is-current' : ''; ?>" href="<?php echo esc_url( home_url( '/solutions/' ) ); ?>"<?php echo $sc_tab_sol ? ' aria-current="page"' : ''; ?>>
		<span class="sc-tabbar__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><rect x="3.5" y="3.5" width="7" height="7" rx="1.6"/><rect x="13.5" y="3.5" width="7" height="7" rx="1.6"/><rect x="3.5" y="13.5" width="7" height="7" rx="1.6"/><rect x="13.5" y="13.5" width="7" height="7" rx="1.6"/></svg></span>
		<span class="sc-tabbar__label"><?php esc_html_e( 'Solutions', 'soundcreations-rwanda' ); ?></span>
	</a>
	<a class="sc-tabbar__item sc-tabbar__item--cta<?php echo $sc_tab_consult ? ' is-current' : ''; ?>" href="<?php echo esc_url( home_url( '/request-a-consultation/' ) ); ?>"<?php echo $sc_tab_consult ? ' aria-current="page"' : ''; ?>>
		<span class="sc-tabbar__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M9 4h6a1 1 0 0 1 1 1v1H8V5a1 1 0 0 1 1-1z"/><path d="M8 5.5H6.5A1.5 1.5 0 0 0 5 7v12.5A1.5 1.5 0 0 0 6.5 21h11a1.5 1.5 0 0 0 1.5-1.5V7a1.5 1.5 0 0 0-1.5-1.5H16"/><path d="M8.5 13.5l2.3 2.3 4.7-4.8"/></svg></span>
		<span class="sc-tabbar__label"><?php esc_html_e( 'Get a Quote', 'soundcreations-rwanda' ); ?></span>
	</a>
	<?php if ( '' !== $sc_bar_tel ) : ?>
	<a class="sc-tabbar__item sc-tabbar__item--call" href="tel:<?php echo esc_attr( $sc_bar_tel ); ?>">
		<span class="sc-tabbar__icon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M21 16.4v2.8a1.9 1.9 0 0 1-2.1 1.9 18.8 18.8 0 0 1-8.2-2.9 18.5 18.5 0 0 1-5.7-5.7A18.8 18.8 0 0 1 2.1 4.2 1.9 1.9 0 0 1 4 2.1h2.8a1.9 1.9 0 0 1 1.9 1.6c.1.9.3 1.7.6 2.5a1.9 1.9 0 0 1-.4 2L7.7 9.4a15.2 15.2 0 0 0 5.7 5.7l1.2-1.2a1.9 1.9 0 0 1 2-.4c.8.3 1.6.5 2.5.6a1.9 1.9 0 0 1 1.9 2z"/></svg></span>
		<span class="sc-tabbar__label"><?php esc_html_e( 'Call', 'soundcreations-rwanda' ); ?></span>
	</a>
	<?php endif; ?>
	<button type="button" class="sc-tabbar__item sc-tabbar__menu" data-sc-menu-toggle aria-controls="sc-primary-nav" aria-expanded="false">
		<span class="sc-tabbar__icon">
			<svg class="sc-tabbar__ico-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" aria-hidden="true" focusable="false"><path d="M4 7h16M4 12h16M4 17h10"/></svg>
			<svg class="sc-tabbar__ico-close" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" aria-hidden="true" focusable="false"><path d="M6 6l12 12M18 6L6 18"/></svg>
		</span>
		<span class="sc-tabbar__label"><span class="sc-tabbar__txt-open"><?php esc_html_e( 'Menu', 'soundcreations-rwanda' ); ?></span><span class="sc-tabbar__txt-close"><?php esc_html_e( 'Close', 'soundcreations-rwanda' ); ?></span></span>
	</button>
</nav>
<?php wp_footer(); ?>
</body>
</html>
