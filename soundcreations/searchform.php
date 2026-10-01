<?php
/**
 * Search form.
 *
 * @package SoundCreations
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<form role="search" method="get" class="sc-search" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="sc-s"><?php esc_html_e( 'Search', 'soundcreations' ); ?></label>
	<input type="search" id="sc-s" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search products, solutions, projects', 'soundcreations' ); ?>">
	<button class="sc-btn sc-btn--primary" type="submit"><?php esc_html_e( 'Search', 'soundcreations' ); ?></button>
</form>
