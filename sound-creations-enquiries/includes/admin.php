<?php
/**
 * Admin: enquiry list columns and notification-recipient settings.
 *
 * @package SoundCreationsEnquiries
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter(
	'manage_sc_enquiry_posts_columns',
	function ( $cols ) {
		return array(
			'cb'         => isset( $cols['cb'] ) ? $cols['cb'] : '',
			'title'      => 'Enquiry',
			'sc_type'    => 'Type',
			'sc_country' => 'Country',
			'sc_email'   => 'Email',
			'date'       => isset( $cols['date'] ) ? $cols['date'] : 'Date',
		);
	}
);

add_action(
	'manage_sc_enquiry_posts_custom_column',
	function ( $col, $post_id ) {
		if ( 'sc_type' === $col ) {
			echo esc_html( get_post_meta( $post_id, '_sc_type', true ) );
		} elseif ( 'sc_country' === $col ) {
			echo esc_html( get_post_meta( $post_id, '_sc_country', true ) );
		} elseif ( 'sc_email' === $col ) {
			echo esc_html( get_post_meta( $post_id, '_sc_email', true ) );
		}
	},
	10,
	2
);

// Notification-recipient routing settings.
add_action(
	'admin_menu',
	function () {
		$parent = 'sc-settings'; // Provided by Sound Creations Core.
		add_submenu_page( $parent, 'Enquiry Routing', 'Enquiry Routing', 'manage_options', 'sc-enq-routing', 'sc_enq_render_routing_page' );
	},
	20
);

add_action(
	'admin_init',
	function () {
		register_setting(
			'sc_enq_routing_group',
			'sc_enq_recipients',
			array(
				'type'              => 'array',
				'sanitize_callback' => 'sc_enq_sanitize_recipients',
				'default'           => array(),
			)
		);
	}
);

function sc_enq_recipient_types() {
	return array(
		'default'      => 'Default (all enquiries)',
		'contact'      => 'Contact / General Enquiry',
		'consultation' => 'Consultation',
		'quote'        => 'Quote',
		'product'      => 'Product Enquiry',
		'dealer'       => 'Dealer Application',
		'fane'         => 'FANE Partnership',
		'support'      => 'Technical Support',
	);
}

function sc_enq_sanitize_recipients( $input ) {
	$clean = array();
	if ( ! is_array( $input ) ) {
		return $clean;
	}
	foreach ( sc_enq_recipient_types() as $key => $label ) {
		if ( empty( $input[ $key ] ) ) {
			continue;
		}
		$val = sanitize_email( trim( (string) $input[ $key ] ) );
		if ( $val ) {
			$clean[ $key ] = $val;
		}
	}
	return $clean;
}

function sc_enq_render_routing_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$opts = get_option( 'sc_enq_recipients', array() );
	?>
	<div class="wrap">
		<h1>Enquiry Routing</h1>
		<p>Where enquiry notifications are emailed. Leave a form type blank to use the Default. Leave Default blank to fall back to the central email in Sound Creations settings.</p>
		<form method="post" action="options.php">
			<?php settings_fields( 'sc_enq_routing_group' ); ?>
			<table class="form-table" role="presentation"><tbody>
			<?php
			foreach ( sc_enq_recipient_types() as $key => $label ) {
				$val = isset( $opts[ $key ] ) ? $opts[ $key ] : '';
				printf(
					'<tr><th scope="row"><label for="scr_%1$s">%2$s</label></th><td><input type="email" id="scr_%1$s" name="sc_enq_recipients[%1$s]" value="%3$s" class="regular-text"></td></tr>',
					esc_attr( $key ),
					esc_html( $label ),
					esc_attr( $val )
				);
			}
			?>
			</tbody></table>
			<?php submit_button( 'Save routing' ); ?>
		</form>
	</div>
	<?php
}
