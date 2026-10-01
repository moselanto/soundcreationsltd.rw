<?php
/**
 * Header template.
 *
 * @package SoundCreations
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div class="sc-preload" role="status" aria-live="polite" aria-label="<?php esc_attr_e( 'Loading', 'soundcreations' ); ?>">
	<div class="sc-wavefront" aria-hidden="true">
		<span class="sc-wavefront__ring"></span>
		<span class="sc-wavefront__ring"></span>
		<span class="sc-wavefront__ring"></span>
		<span class="sc-wavefront__ring"></span>
		<span class="sc-wavefront__core"></span>
	</div>
	<p class="sc-preload__label"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></p>
</div>
<a class="sc-skip-link" href="#sc-main"><?php esc_html_e( 'Skip to content', 'soundcreations' ); ?></a>

<div class="sc-utility">
	<div class="sc-container sc-utility__inner">
		<div class="sc-utility__zone sc-utility__zone--left"><span class="sc-utility__regions"><?php echo esc_html( sc_setting( 'regions' ) ); ?></span></div>
		<div class="sc-utility__zone sc-utility__zone--center"><a href="tel:<?php echo esc_attr( sc_setting( 'phone_link' ) ); ?>"><?php echo esc_html( sc_setting( 'phone' ) ); ?></a><span class="sc-utility__dot" aria-hidden="true"> &middot; </span><a href="mailto:<?php echo esc_attr( sc_setting( 'email' ) ); ?>"><?php echo esc_html( sc_setting( 'email' ) ); ?></a></div>
		<div class="sc-utility__zone sc-utility__zone--right"><span class="sc-utility__hours"><?php echo esc_html( sc_setting( 'hours_week' ) ); ?></span><span class="sc-utility__social"><?php sc_utility_social(); ?></span></div>
	</div>
</div>

<header class="sc-header">
	<div class="sc-container sc-header__inner">
		<div class="sc-brand">
			<?php
			echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="sc-logo" rel="home">'
			. '<img class="sc-logo__img sc-logo__img--dark" src="' . esc_url( SC_THEME_URI . '/assets/img/logo-white.webp' ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" width="309" height="115">'
			. '<img class="sc-logo__img sc-logo__img--light" src="' . esc_url( SC_THEME_URI . '/assets/img/logo-color.webp' ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" width="309" height="115">'
			. '</a>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- first-party logo markup.
			?>
		</div>

		<nav class="sc-nav" id="sc-primary-nav" aria-label="<?php esc_attr_e( 'Primary', 'soundcreations' ); ?>">
				<div class="sc-nav__head">
					<span class="sc-nav__title"><?php esc_html_e( 'Menu', 'soundcreations' ); ?></span>
					<button class="sc-nav__close" type="button" data-sc-menu-close aria-label="<?php esc_attr_e( 'Close menu', 'soundcreations' ); ?>">&times;</button>
				</div>
			<?php
			// Render the assigned Primary menu, but only if it actually has items;
			// otherwise fall back to the built-in menu so the drawer is never empty.
			$sc_locs        = get_nav_menu_locations();
			$sc_has_primary = false;
			if ( isset( $sc_locs['primary'] ) && $sc_locs['primary'] ) {
				$sc_menu_items = wp_get_nav_menu_items( (int) $sc_locs['primary'] );
				if ( is_array( $sc_menu_items ) && count( $sc_menu_items ) > 0 ) {
					$sc_has_primary = true;
				}
			}
			if ( $sc_has_primary ) {
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'sc-nav__list',
						'depth'          => 2,
					)
				);
			} else {
				sc_primary_menu_fallback();
			}
			?>
			<a class="sc-btn sc-btn--primary sc-nav__cta" href="<?php echo esc_url( home_url( '/request-a-consultation/' ) ); ?>"><?php esc_html_e( 'Request a Consultation', 'soundcreations' ); ?> <span class="sc-nav__cta-arrow" aria-hidden="true">&rarr;</span></a>
			<div class="sc-nav__foot">
				<span class="sc-nav__foot-title"><?php esc_html_e( 'Get in touch', 'soundcreations' ); ?></span>
				<a class="sc-nav__foot-link" href="tel:<?php echo esc_attr( sc_setting( 'phone_link' ) ); ?>"><?php echo esc_html( sc_setting( 'phone' ) ); ?></a>
				<a class="sc-nav__foot-link" href="mailto:<?php echo esc_attr( sc_setting( 'email' ) ); ?>"><?php echo esc_html( sc_setting( 'email' ) ); ?></a>
				<span class="sc-nav__social"><?php sc_utility_social(); ?></span>
			</div>
			</nav>

		<div class="sc-header__actions">
			<a class="sc-btn sc-btn--primary sc-header__cta" href="<?php echo esc_url( home_url( '/request-a-consultation/' ) ); ?>"><?php esc_html_e( 'Request a Consultation', 'soundcreations' ); ?></a>
			<?php sc_theme_toggle_button(); ?>
			<button class="sc-menu-toggle" type="button" data-sc-menu-toggle aria-expanded="false" aria-label="<?php esc_attr_e( 'Menu', 'soundcreations' ); ?>">&#9776;</button>
		</div>
	</div>
<div class="sc-nav-scrim" data-sc-menu-close aria-hidden="true"></div>
	</header>

<main id="sc-main">
<?php
/**
 * Simple menu fallback so the header is never empty before menus are assigned.
 */
function sc_primary_menu_fallback() {
	$menu = array(
		array( 'Solutions', '/solutions/', array(
			array( 'All Solutions', '/solutions/' ),
			array( 'Professional Audio', '/solutions/professional-audio/' ),
			array( 'Acoustics', '/solutions/acoustics/' ),
			array( 'Conferencing', '/solutions/conferencing/' ),
			array( 'System Integration', '/solutions/system-integration/' ),
		) ),
		array( 'Products', '/brands/', array(
			array( 'All Products', '/brands/' ),
			array( 'Fane Africa', '/fane/' ),
		) ),
		array( 'Projects', '/projects/', array() ),
		array( 'Fane Africa', '/fane/', array() ),
		array( 'Videos', '/videos/', array() ),
		array( 'About Us', '/about/', array(
			array( 'About Sound Creations', '/about/' ),
			array( 'Training', '/training/' ),
			array( 'Request a Consultation', '/request-a-consultation/' ),
		) ),
		array( 'Rwanda', 'https://rwanda.soundcreationsltd.com/', array() ),
	);
	echo '<ul class="sc-nav__list">';
	foreach ( $menu as $item ) {
		$has = count( $item[2] ) > 0;
		echo '<li class="' . ( $has ? 'menu-item-has-children' : '' ) . '">';
		$sc_ext  = ( 0 === strpos( $item[1], 'http' ) );
		$sc_href = $sc_ext ? $item[1] : home_url( $item[1] );
		echo '<a href="' . esc_url( $sc_href ) . '"' . ( $sc_ext ? ' target="_blank" rel="noopener"' : '' ) . '>' . esc_html( $item[0] ) . '</a>';
		if ( $has ) {
			echo '<ul class="sub-menu">';
			foreach ( $item[2] as $sub ) {
				$sc_sub_href = ( 0 === strpos( $sub[1], 'http' ) ) ? $sub[1] : home_url( $sub[1] );
				echo '<li><a href="' . esc_url( $sc_sub_href ) . '">' . esc_html( $sub[0] ) . '</a></li>';
			}
			echo '</ul>';
		}
		echo '</li>';
	}
	echo '</ul>';
}
