<?php
/**
 * 404 template.
 *
 * @package SoundCreations
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>
<div class="sc-container sc-page" style="text-align:center;">
	<p class="sc-eyebrow">404</p>
	<h1><?php esc_html_e( 'Page not found', 'soundcreations' ); ?></h1>
	<p class="sc-lead" style="margin:0 auto 2rem;"><?php esc_html_e( 'The page you are looking for may have moved. Let us point you back to the right place.', 'soundcreations' ); ?></p>
	<a class="sc-btn sc-btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back to home', 'soundcreations' ); ?></a>
</div>
<?php
get_footer();
