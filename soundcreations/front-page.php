<?php
/**
 * Homepage template. Cinematic dark hero + narrative sections.
 * All copy is editable in wp-admin: Sound Creations -> Settings (Homepage section).
 * Solution cards and Featured Projects are pulled live from the Solutions and
 * Projects you manage in wp-admin (with a safe fallback if none exist yet).
 *
 * @package SoundCreations
 */

if ( defined( 'ABSPATH' ) === false ) {
	exit;
}
get_header();
get_template_part( 'template-parts/designs/home' );
get_footer();
