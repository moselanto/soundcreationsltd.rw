<?php
/**
 * Template for the /yamaha/ page: Sound Creations Rwanda, Authorised Yamaha
 * Distributor in Rwanda. Same idea as the FANE page: recognition for the
 * brand, the range we carry, featured products, Yamaha at work in our
 * projects, and the Kigali showroom.
 *
 * Product cards use the bundled official photos (assets/img/products-official/)
 * and link to each product page; titles come from the product posts when they
 * exist. Edit the lists in the arrays below.
 *
 * @package SoundCreationsRwanda
 */

if ( defined( 'ABSPATH' ) === false ) {
	exit;
}
get_header();
get_template_part( 'template-parts/designs/yamaha' );
get_footer();
