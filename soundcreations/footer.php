<?php
/**
 * Footer template. Three editable columns:
 *   1. About + social   2. Open Hours   3. Contact (head office).
 * Explore and Solutions were removed on 2026-09-25 (owner request) and Open
 * Hours was promoted out of Contact to take the slot Solutions held.
 * Every value is editable in wp-admin: Sound Creations -> Settings (Footer section).
 *
 * @package SoundCreations
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
				<?php if ( $sc_email ) : ?>
					<a href="mailto:<?php echo esc_attr( $sc_email ); ?>"><?php echo esc_html( $sc_email ); ?></a>
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
/* Floating WhatsApp chat button. Number + greeting are set in Sound Creations -> Settings. */
$sc_wa = sc_whatsapp_url();
if ( strlen( $sc_wa ) > 0 ) :
	$sc_wa_msg  = sc_setting( 'whatsapp_prefill', 'Hello Sound Creations, I would like to enquire about your services.' );
	$sc_wa_href = esc_url( $sc_wa . '?text=' . rawurlencode( $sc_wa_msg ) );
	?>
	<a class="sc-wa-float" href="<?php echo $sc_wa_href; ?>" target="_blank" rel="noopener noreferrer nofollow" aria-label="<?php esc_attr_e( 'Chat with us on WhatsApp', 'soundcreations' ); ?>">
		<svg viewBox="0 0 24 24" width="30" height="30" fill="currentColor" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.5 15.3L2 22l4.8-1.3A10 10 0 1 0 12 2zm0 18a8 8 0 0 1-4.1-1.1l-.3-.2-2.8.7.8-2.7-.2-.3A8 8 0 1 1 12 20zm4.4-6c-.2-.1-1.4-.7-1.6-.8s-.4-.1-.5.1-.6.8-.8 1-.3.2-.5.1a6.5 6.5 0 0 1-3.2-2.8c-.2-.4.2-.4.6-1.2.1-.2 0-.3 0-.5s-.5-1.3-.7-1.8-.4-.4-.5-.4h-.5a1 1 0 0 0-.7.3 3 3 0 0 0-.9 2.2c0 1.3 1 2.6 1.1 2.8s1.9 3 4.7 4.2c1.7.7 2.4.8 3.2.7.5-.1 1.4-.6 1.6-1.1s.2-1 .1-1.1z"/></svg>
		<span class="sc-wa-float__label"><?php esc_html_e( 'Chat with us', 'soundcreations' ); ?></span>
	</a>
	<style>
	.sc-wa-float{position:fixed;right:20px;bottom:20px;z-index:9999;display:inline-flex;align-items:center;gap:.55rem;background:#25D366;color:#fff;padding:12px 18px 12px 14px;border-radius:999px;box-shadow:0 8px 24px rgba(0,0,0,.28);text-decoration:none;font-weight:700;font-size:14px;line-height:1;transition:transform .18s ease,box-shadow .18s ease}
	.sc-wa-float:hover,.sc-wa-float:focus-visible{transform:translateY(-2px);box-shadow:0 12px 30px rgba(0,0,0,.34);color:#fff}
	.sc-wa-float svg{flex:0 0 auto}
	.sc-wa-float__label{white-space:nowrap}
	@media (max-width:600px){.sc-wa-float{right:14px;bottom:14px;padding:12px}.sc-wa-float__label{display:none}}
	@media (max-width:782px){.sc-wa-float{display:none}}
	@media (prefers-reduced-motion:reduce){.sc-wa-float{transition:none}}
	</style>
	<?php
endif;
?>
<?php
/* Mobile sticky action bar - keeps the primary CTA and quick contact reachable on phones. */
$sc_bar_tel = sc_setting( 'phone_link' );
$sc_bar_wa  = sc_whatsapp_url();
?>
<div class="sc-mobabar" role="navigation" aria-label="<?php esc_attr_e( 'Quick actions', 'soundcreations' ); ?>">
	<?php if ( strlen( (string) $sc_bar_tel ) > 0 ) : ?>
	<a class="sc-mobabar__btn sc-mobabar__btn--call" href="tel:<?php echo esc_attr( $sc_bar_tel ); ?>">
		<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2 4.2 2 2 0 0 1 4 2h3a2 2 0 0 1 2 1.7c.1.9.3 1.8.6 2.6a2 2 0 0 1-.5 2.1L7.6 9.8a16 16 0 0 0 6 6l1.4-1.4a2 2 0 0 1 2.1-.5c.8.3 1.7.5 2.6.6A2 2 0 0 1 22 16.9z"/></svg>
		<span><?php esc_html_e( 'Call', 'soundcreations' ); ?></span>
	</a>
	<?php endif; ?>
	<?php if ( strlen( $sc_bar_wa ) > 0 ) : ?>
	<a class="sc-mobabar__btn sc-mobabar__btn--wa" href="<?php echo esc_url( $sc_bar_wa ); ?>" target="_blank" rel="noopener noreferrer nofollow">
		<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor" aria-hidden="true"><path d="M12 2a10 10 0 0 0-8.5 15.3L2 22l4.8-1.3A10 10 0 1 0 12 2zm0 18a8 8 0 0 1-4.1-1.1l-.3-.2-2.8.7.8-2.7-.2-.3A8 8 0 1 1 12 20z"/></svg>
		<span><?php esc_html_e( 'WhatsApp', 'soundcreations' ); ?></span>
	</a>
	<?php endif; ?>
	<a class="sc-mobabar__btn sc-mobabar__btn--cta" href="<?php echo esc_url( home_url( '/request-a-consultation/' ) ); ?>">
		<span><?php esc_html_e( 'Get a Consultation', 'soundcreations' ); ?></span>
		<span aria-hidden="true">&rarr;</span>
	</a>
</div>
<?php wp_footer(); ?>
</body>
</html>
