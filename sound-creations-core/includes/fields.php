<?php
/**
 * Editorial custom fields for Products, Brands and Projects.
 *
 * @package SoundCreationsCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function sc_core_field_groups() {
	return array(
		'sc_solution' => array(
			'title'  => 'Solution Details',
			'fields' => array(
				array( 'summary', 'One-line summary', 'text', 'Shown under the title and on cards' ),
				array( 'includes', 'What is included', 'textarea', 'One item per line' ),
				array( 'outcome', 'Key outcome', 'text', 'e.g. Intelligible speech in a reverberant hall' ),
			),
		),
		'sc_product' => array(
			'title'  => 'Product Details',
			'fields' => array(
				array( 'brand_name', 'Brand', 'text', 'e.g. FANE, NEXO, Shure' ),
				array( 'model', 'Model / SKU', 'text', '' ),
				array( 'availability', 'Availability', 'text', 'e.g. In stock, Available on indent' ),
				array( 'datasheet', 'Datasheet URL', 'url', 'Link to the manufacturer datasheet (PDF)' ),
				array( 'specs', 'Key specifications', 'textarea', 'One per line as Label: Value (e.g. Power: 600 W AES)' ),
			),
		),
		'sc_brand'   => array(
			'title'  => 'Brand Details',
			'fields' => array(
				array( 'origin', 'Country of origin', 'text', '' ),
				array( 'category', 'Category', 'text', 'e.g. Loudspeakers, DSP, Acoustics' ),
				array( 'tagline', 'Short tagline', 'text', '' ),
				array( 'website', 'Brand website', 'url', '' ),
			),
		),
		'sc_project' => array(
			'title'  => 'Project Details',
			'fields' => array(
				array( 'client', 'Client / venue', 'text', '' ),
				array( 'location', 'Location', 'text', '' ),
				array( 'year', 'Year completed', 'text', '' ),
				array( 'summary', 'One-line summary', 'text', '' ),
				array( 'challenge', 'The Challenge', 'textarea', 'The brief or problem. Leave a blank line between paragraphs.' ),
				array( 'solution', 'The Solution', 'textarea', 'What Sound Creations designed and delivered. Blank line between paragraphs.' ),
				array( 'technology', 'The Technology', 'textarea', 'Equipment and brands used. One item per line shows as a checklist.' ),
				array( 'result', 'The Result', 'textarea', 'The outcome and impact. Blank line between paragraphs.' ),
				array( 'scope', 'Scope of work', 'textarea', 'One item per line' ),
				array( 'brands_used', 'Brands used', 'text', 'Comma separated (optional if using the Brand taxonomy)' ),
				array( 'gallery', 'Photos (gallery)', 'gallery', 'Click Add / edit photos to pick from the Media Library. Selection order is the display order.' ),
			),
		),
		'sc_resource' => array(
			'title'  => 'Resource / Video Details',
			'fields' => array(
				array( 'video_url', 'YouTube video URL', 'url', 'Paste a YouTube link (watch, youtu.be or shorts) to show this resource as a playable video on the Resources page.' ),
				array( 'file', 'Download file URL', 'url', 'Optional. Upload a file to the Media Library and paste its link for a downloadable resource (PDF, etc.).' ),
			),
		),
	);
}

add_action(
	'add_meta_boxes',
	function () {
		foreach ( sc_core_field_groups() as $pt => $group ) {
			add_meta_box( 'sc_fields_' . $pt, $group['title'], 'sc_core_render_meta_box', $pt, 'normal', 'high' );
		}
	}
);

function sc_core_render_meta_box( $post ) {
	$groups = sc_core_field_groups();
	if ( ! isset( $groups[ $post->post_type ] ) ) {
		return;
	}
	wp_nonce_field( 'sc_core_fields', 'sc_core_fields_nonce' );
	echo '<style>.sc-mb{display:grid;gap:1rem;margin-top:.5rem;}.sc-mb label{font-weight:600;display:block;margin-bottom:.25rem;}.sc-mb input[type=text],.sc-mb input[type=url],.sc-mb textarea{width:100%;}.sc-mb .desc{color:#666;font-size:12px;margin:.2rem 0 0;}</style>';
	echo '<div class="sc-mb">';
	foreach ( $groups[ $post->post_type ]['fields'] as $f ) {
		list( $key, $label, $type, $help ) = $f;
		$val = get_post_meta( $post->ID, '_sc_' . $key, true );
		$id  = 'sc_' . $key;
		echo '<div>';
		echo '<label for="' . esc_attr( $id ) . '">' . esc_html( $label ) . '</label>';
		if ( 'textarea' === $type ) {
			echo '<textarea id="' . esc_attr( $id ) . '" name="sc_fields[' . esc_attr( $key ) . ']" rows="5">' . esc_textarea( $val ) . '</textarea>';
		} elseif ( 'gallery' === $type ) {
			$gids = array_filter( array_map( 'absint', explode( ',', (string) $val ) ) );
			echo '<div class="sc-gal" data-sc-gal>';
			echo '<input type="hidden" class="sc-gal-input" name="sc_fields[' . esc_attr( $key ) . ']" value="' . esc_attr( implode( ',', $gids ) ) . '">';
			echo '<div class="sc-gal-prev" style="display:flex;flex-wrap:wrap;gap:6px;margin:.25rem 0 .5rem;">';
			foreach ( $gids as $gid ) {
				$thumb = wp_get_attachment_image( $gid, array( 84, 84 ), false, array( 'style' => 'width:84px;height:84px;object-fit:cover;border-radius:6px;' ) );
				if ( $thumb ) {
					echo '<span class="sc-gal-item">' . $thumb . '</span>';
				}
			}
			echo '</div>';
			echo '<p><button type="button" class="button button-primary sc-gal-add">Add / edit photos</button> <button type="button" class="button sc-gal-clear">Clear</button></p>';
			echo '</div>';
		} else {
			$it = ( 'url' === $type ) ? 'url' : 'text';
			echo '<input type="' . esc_attr( $it ) . '" id="' . esc_attr( $id ) . '" name="sc_fields[' . esc_attr( $key ) . ']" value="' . esc_attr( $val ) . '">';
		}
		if ( $help ) {
			echo '<p class="desc">' . esc_html( $help ) . '</p>';
		}
		echo '</div>';
	}
	echo '</div>';
}

add_action(
	'save_post',
	function ( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! isset( $_POST['sc_core_fields_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sc_core_fields_nonce'] ) ), 'sc_core_fields' ) ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		$groups = sc_core_field_groups();
		$pt     = get_post_type( $post_id );
		if ( ! isset( $groups[ $pt ] ) ) {
			return;
		}
		$in = ( isset( $_POST['sc_fields'] ) && is_array( $_POST['sc_fields'] ) ) ? wp_unslash( $_POST['sc_fields'] ) : array();
		foreach ( $groups[ $pt ]['fields'] as $f ) {
			list( $key, $label, $type, $help ) = $f;
			$raw = isset( $in[ $key ] ) ? $in[ $key ] : '';
			if ( 'textarea' === $type ) {
				$clean = sanitize_textarea_field( $raw );
			} elseif ( 'gallery' === $type ) {
				$gids  = array_filter( array_map( 'absint', explode( ',', (string) $raw ) ) );
				$clean = implode( ',', $gids );
			} elseif ( 'url' === $type ) {
				$clean = esc_url_raw( trim( $raw ) );
			} else {
				$clean = sanitize_text_field( $raw );
			}
			update_post_meta( $post_id, '_sc_' . $key, $clean );
		}
	}
);


add_action(
	'admin_enqueue_scripts',
	function ( $hook ) {
		if ( in_array( $hook, array( 'post.php', 'post-new.php' ), true ) === false ) {
			return;
		}
		$groups = sc_core_field_groups();
		$pt     = get_post_type();
		if ( isset( $groups[ $pt ] ) === false ) {
			return;
		}
		$has_gallery = false;
		foreach ( $groups[ $pt ]['fields'] as $f ) {
			if ( 'gallery' === $f[2] ) {
				$has_gallery = true;
			}
		}
		if ( $has_gallery === false ) {
			return;
		}
		wp_enqueue_media();
		wp_enqueue_script(
			'sc-admin-gallery',
			plugins_url( 'assets/admin-gallery.js', dirname( __DIR__ ) . '/sound-creations-core.php' ),
			array( 'jquery' ),
			'1.0.0',
			true
		);
	}
);
