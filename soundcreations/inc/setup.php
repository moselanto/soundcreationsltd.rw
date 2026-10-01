<?php
/**
 * Theme setup: supports, menus, widget areas.
 *
 * @package SoundCreations
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'after_setup_theme',
	function () {
		load_theme_textdomain( 'soundcreations', SC_THEME_DIR . '/languages' );

		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
		add_theme_support( 'custom-logo', array( 'height' => 48, 'width' => 220, 'flex-height' => true, 'flex-width' => true ) );
		add_theme_support( 'align-wide' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'editor-styles' );

		if ( isset( $GLOBALS['content_width'] ) === false ) {
			$GLOBALS['content_width'] = 1200;
		}

		register_nav_menus(
			array(
				'primary'          => __( 'Primary Menu', 'soundcreations' ),
				'footer_solutions' => __( 'Footer - Solutions', 'soundcreations' ),
				'footer_business'  => __( 'Footer - Business', 'soundcreations' ),
			)
		);
	}
);

add_action(
	'widgets_init',
	function () {
		register_sidebar(
			array(
				'name'          => __( 'Footer', 'soundcreations' ),
				'id'            => 'footer-1',
				'before_widget' => '<div class="sc-widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h4 class="sc-widget__title">',
				'after_title'   => '</h4>',
			)
		);
	}
);
