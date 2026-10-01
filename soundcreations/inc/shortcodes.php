<?php
/**
 * Front-end shortcodes.
 *
 * @package SoundCreations
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Presence locations. Marker x/y are percentages of the map image and are
 * easy to nudge. The text list is the source of truth and is always correct,
 * independent of the map graphic. All four presences are confirmed.
 */
function sc_africa_map_locations() {
	return array(
		array( 'id' => 'nairobi', 'name' => 'Nairobi, Kenya', 'role' => 'Head office', 'note' => 'Mpaka Plaza, Mpaka Road, Westlands - our head office and technical base.', 'x' => 62, 'y' => 61, 'map' => true ),
		array( 'id' => 'kigali', 'name' => 'Kigali, Rwanda', 'role' => 'Branch', 'note' => 'Regional branch serving Rwanda and the wider region.', 'x' => 56, 'y' => 63, 'map' => true ),
		array( 'id' => 'drc', 'name' => 'DRC Congo', 'role' => 'Distribution & projects', 'note' => 'Distribution and professional sound project delivery across the DRC.', 'x' => 46, 'y' => 64, 'map' => true ),
		array( 'id' => 'dubai', 'name' => 'Dubai, UAE', 'role' => 'Middle East hub', 'note' => 'Our UAE base for Middle East sourcing, logistics and distribution.', 'x' => 79, 'y' => 33, 'map' => true ),
	);
}

function sc_render_africa_map( $atts = array() ) {
	$locs = sc_africa_map_locations();
	$img  = SC_THEME_URI . '/assets/img/map-africa-me.jpg';
	ob_start();
	?>
	<div class="sc-map">
		<div class="sc-map__stage">
			<img class="sc-map__bg" src="<?php echo esc_url( $img ); ?>" alt="<?php esc_attr_e( 'Map of Africa and the Middle East showing Sound Creations regional presence', 'soundcreations' ); ?>" loading="lazy">
			<?php
			foreach ( $locs as $l ) {
				if ( empty( $l['map'] ) ) {
					continue;
				}
				printf(
					'<button type="button" class="sc-map__pin" data-loc="%1$s" style="left:%2$s%%;top:%3$s%%;" aria-label="%4$s"><span class="sc-map__dot"></span></button>',
					esc_attr( $l['id'] ),
					esc_attr( (string) (float) $l['x'] ),
					esc_attr( (string) (float) $l['y'] ),
					esc_attr( $l['name'] )
				);
			}
			?>
		</div>
		<div class="sc-map__panel">
			<p class="sc-eyebrow"><?php esc_html_e( 'Regional presence', 'soundcreations' ); ?></p>
			<ul class="sc-map__list">
				<?php foreach ( $locs as $l ) : ?>
					<li class="sc-map__item" data-loc="<?php echo esc_attr( $l['id'] ); ?>" tabindex="0">
						<div class="sc-map__itemhead"><span class="sc-map__name"><?php echo esc_html( $l['name'] ); ?></span><span class="sc-map__role"><?php echo esc_html( $l['role'] ); ?></span></div>
						<p class="sc-map__note"><?php echo esc_html( $l['note'] ); ?></p>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'sc_africa_map', 'sc_render_africa_map' );


/**
 * Trusted partners / brands marquee. Pulls from the Brands (sc_brand) CPT so the
 * list is editable in the WordPress admin. Set each brand logo as its Featured
 * Image; brands with no logo fall back to a styled text wordmark. Reorder via the
 * page Order attribute. If no brands exist yet, a sensible default set is shown.
 */
function sc_partners_default_names() {
	return array( 'FANE', 'dB Technologies', 'Shure', 'Bose Professional', 'Midas', 'Allen & Heath', 'Aid', 'Asona', 'Yamaha', 'Barrisol', 'Rockfon', 'Hikvision', 'Tiange', 'Sommer Cable' );
}

function sc_render_partners( $atts = array() ) {
	$items = array();

	if ( post_type_exists( 'sc_brand' ) ) {
		$q = new WP_Query(
			array(
				'post_type'      => 'sc_brand',
				'posts_per_page' => 40,
				'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
				'order'          => 'ASC',
				'no_found_rows'  => true,
			)
		);
		while ( $q->have_posts() ) {
			$q->the_post();
			$logo = '';
			if ( has_post_thumbnail() ) {
				$logo = get_the_post_thumbnail(
					get_the_ID(),
					'medium',
					array( 'class' => 'sc-partner__logo', 'loading' => 'lazy', 'alt' => esc_attr( get_the_title() ) )
				);
			} else {
				// Fall back to the bundled full-colour brand logo (assets/img/brands/logos/{logo}.png).
				$logo_key = get_post_meta( get_the_ID(), '_sc_logo', true );
				if ( $logo_key ) {
					$rel = 'assets/img/brands/logos/' . $logo_key . '.png';
					if ( file_exists( get_theme_file_path( $rel ) ) ) {
						$logo = '<img class="sc-partner__logo" src="' . esc_url( get_theme_file_uri( $rel ) ) . '" alt="' . esc_attr( get_the_title() ) . '" loading="lazy">';
					}
				}
			}
			$items[] = array( 'name' => get_the_title(), 'logo' => $logo );
		}
		wp_reset_postdata();
	}

	if ( empty( $items ) ) {
		foreach ( sc_partners_default_names() as $sc_n ) {
			$items[] = array( 'name' => $sc_n, 'logo' => '' );
		}
	}

	$one = '';
	foreach ( $items as $sc_it ) {
		if ( $sc_it['logo'] ) {
			$one .= '<span class="sc-partner">' . $sc_it['logo'] . '</span>';
		} else {
			$one .= '<span class="sc-partner sc-partner--text">' . esc_html( $sc_it['name'] ) . '</span>';
		}
	}

	$set   = '<div class="sc-partners__set">' . $one . '</div>';
	$track = '<div class="sc-partners__track">' . $set . $set . '</div>';
	return '<div class="sc-partners" data-sc-partners>' . $track . '</div>';
}
add_shortcode( 'sc_partners', 'sc_render_partners' );


add_shortcode( 'sc_profiles', 'sc_render_profiles' );
/**
 * Downloadable company profiles section. Usage: [sc_profiles]
 * Cards, copy and PDF URLs are editable in Sound Creations -> Settings.
 */
function sc_render_profiles( $atts = array() ) {
	// Company Profile is the only downloadable profile (2026-09-25 owner
	// request). The Acoustic Profile card was removed; its copy and PDF-URL
	// settings were retired with it. Kept as an array so a second profile can
	// be reinstated later without reworking the markup -- the grid below is
	// auto-fit, so one card fills the row and two split it.
	$cards = array(
		array(
			'title' => 'Company Profile',
			'desc'  => sc_setting( 'company_profile_desc' ),
			'url'   => sc_setting( 'company_profile_url', '' ),
			'icon'  => 'building',
		),
	);
	$icons = array(
		'building' => '<path d="M3 21h18"/><path d="M6 21V4a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v17"/><path d="M14 21V9h3a1 1 0 0 1 1 1v11"/><path d="M9 7h2"/><path d="M9 11h2"/><path d="M9 15h2"/>',
	);
	ob_start();
	?>
	<div class="sc-profiles sc-profiles--compact">
		<div class="sc-profiles__head">
			<p class="sc-eyebrow"><?php echo esc_html( sc_setting( 'profiles_eyebrow' ) ); ?></p>
		</div>
		<div class="sc-profiles__grid">
			<?php foreach ( $cards as $card ) : ?>
				<div class="sc-profile-card">
					<span class="sc-profile-card__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><?php echo isset( $icons[ $card['icon'] ] ) ? $icons[ $card['icon'] ] : ''; ?></svg></span>
					<div class="sc-profile-card__body">
						<h3><?php echo esc_html( $card['title'] ); ?></h3>
						<p><?php echo sc_rich_e( $card['desc'] ); ?></p>
					</div>
					<?php if ( '' === $card['url'] ) : ?>
						<span class="sc-profile-card__soon"><?php esc_html_e( 'PDF available soon', 'soundcreations' ); ?></span>
					<?php else : ?>
						<a class="sc-btn sc-btn--primary sc-profile-card__btn" href="<?php echo esc_url( $card['url'] ); ?>" download target="_blank" rel="noopener"><span><?php esc_html_e( 'Download PDF', 'soundcreations' ); ?></span><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" width="18" height="18"><path d="M12 3v12"/><path d="m7 12 5 5 5-5"/><path d="M5 21h14"/></svg></a>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
