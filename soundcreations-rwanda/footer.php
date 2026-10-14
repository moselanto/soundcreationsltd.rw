<?php
/**
 * Footer template. Three editable columns:
 *   1. About + social   2. Open Hours   3. Contact (head office).
 * Explore and Solutions were removed on 2026-09-25 (owner request) and Open
 * Hours was promoted out of Contact to take the slot Solutions held.
 * Every value is editable in wp-admin: Sound Creations -> Settings (Footer section).
 *
 * Child copy of the parent footer.php. Only change: the old floating WhatsApp
 * link and mobile action bar are removed (see inc/whatsapp-chat.php).
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
/*
 * Rwanda override (1.9.0): the floating WhatsApp chat and the mobile dock
 * (Home, Call, Consultation, Menu) are printed by inc/whatsapp-chat.php on
 * wp_footer, replacing the parent's plain WhatsApp link and Call/WhatsApp bar.
 */
?>
<?php wp_footer(); ?>
</body>
</html>
