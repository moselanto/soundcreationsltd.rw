<?php
/**
 * FANE Africa on the Brands page (management request, 8 Oct 2026).
 *
 * FANE Africa left the top menu. The Brands page now runs:
 *   hero -> Our represented products brands -> Why professionals trust our
 *   products -> the FANE Africa page (full design, as on /fane/) -> partner CTA.
 * The FANE section is the same template part as the /fane/ page, so edits in
 * Sound Creations -> Settings (FANE fields) show in both places. Its h1 is
 * turned into an h2 here so the Brands page keeps a single h1.
 * The /fane/ page stays live; the menu highlights "Brands" while on it.
 *
 * @package SoundCreationsRwanda
 */

if ( defined( 'ABSPATH' ) === false ) {
	exit;
}

function scrw_render_fane_on_brands() {
	ob_start();
	get_template_part( 'template-parts/designs/fane' );
	$fane = (string) ob_get_clean();
	if ( '' === trim( $fane ) ) {
		return '';
	}
	// One h1 per page: the FANE hero title becomes an h2 on /brands/.
	$fane = preg_replace( '#<h1(\s[^>]*)?>#', '<h2$1>', $fane );
	$fane = str_replace( '</h1>', '</h2>', $fane );
	ob_start();
	?>
	<div class="scrw-fane-embed" id="fane-africa">
		<div class="sc-container">
			<div class="scrw-fane-embed__bar">
				<p class="scrw-fane-embed__badge"><span aria-hidden="true"></span><?php esc_html_e( 'Official FANE Africa partner', 'soundcreations-rwanda' ); ?></p>
				<a class="scrw-fane-embed__link" href="<?php echo esc_url( home_url( '/fane/' ) ); ?>"><?php esc_html_e( 'Open the full FANE Africa page', 'soundcreations-rwanda' ); ?> <span aria-hidden="true">&rarr;</span></a>
			</div>
		</div>
		<?php echo $fane; // phpcs:ignore -- rendered template part, escaped there. ?>
	</div>
	<?php
	return (string) ob_get_clean();
}

add_action(
	'sc_brands_after_why',
	function () {
		echo scrw_render_fane_on_brands(); // phpcs:ignore -- escaped above.
	}
);
