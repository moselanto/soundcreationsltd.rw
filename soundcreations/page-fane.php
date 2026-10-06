<?php
/**
 * FANE brand hub. Auto-applies to the page with slug "fane".
 * Copy is editable in Sound Creations -> Settings where wired via sc_setting().
 *
 * @package SoundCreations
 */

if ( defined( 'ABSPATH' ) === false ) {
	exit;
}
get_header();
get_template_part( 'template-parts/designs/fane' );
get_footer();
