<?php
/**
 * Template Name: Full Width (Elementor / Page Builder)
 *
 * A full-bleed canvas for pages built with Elementor or the block editor.
 * Keeps the site header and footer but drops the narrow reading container,
 * so page-builder sections can span the full viewport width.
 *
 * Assign it per page under Page > Page Attributes > Template, then design
 * the page visually in Elementor. Nothing on the page is hardcoded.
 *
 * @package SoundCreations
 */

if ( defined( 'ABSPATH' ) === false ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();
	?>
	<main class="sc-fullwidth" id="sc-content">
		<?php the_content(); ?>
	</main>
	<?php
endwhile;

get_footer();
