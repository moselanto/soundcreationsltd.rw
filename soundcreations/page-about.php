<?php
/**
 * About page. Auto-applies to the page with slug "about".
 *
 * Simplified layout: a single About + history block with a photo,
 * followed by What We Do, Our Brands, and downloadable Company Profiles.
 * All copy and the photo are editable in Sound Creations -> Settings.
 *
 * @package SoundCreations
 */

if ( defined( 'ABSPATH' ) === false ) {
	exit;
}
get_header();
get_template_part( 'template-parts/designs/about' );
get_footer();
