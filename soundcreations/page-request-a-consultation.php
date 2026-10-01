<?php
/**
 * Request a Consultation page. Auto-applies to the page with slug "request-a-consultation".
 *
 * @package SoundCreations
 */

if ( defined( 'ABSPATH' ) === false ) {
	exit;
}
get_header();
get_template_part( 'template-parts/consult-page' );
get_footer();
