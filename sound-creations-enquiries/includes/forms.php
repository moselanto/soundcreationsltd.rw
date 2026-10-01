<?php
/**
 * Enquiry form definitions, renderer, shortcode and auto-append.
 *
 * @package SoundCreationsEnquiries
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * All enquiry form definitions. Each field: array( name, label, type, required, half, options ).
 */
function sc_enq_forms() {
	$country  = array( 'Kenya', 'Uganda', 'Tanzania', 'Rwanda', 'Burundi', 'Ethiopia', 'South Sudan', 'DR Congo', 'UAE', 'Other' );
	$sector   = array( 'Houses of Worship', 'Corporate & Offices', 'Hospitality', 'Education', 'Entertainment', 'Government & Institutions', 'Professional Audio / Rental', 'OEM / Manufacturing', 'Other' );
	$biztype  = array( 'Dealer / Reseller', 'System Integrator', 'Rental Company', 'Consultant', 'OEM / Cabinet Manufacturer', 'Other' );
	$yesno    = array( 'Yes', 'Limited', 'No' );
	$orgtype  = array( 'OEM Loudspeaker Manufacturer', 'Cabinet Manufacturer', 'System Integrator', 'Rental Company', 'Church', 'Consultant', 'Dealer / Reseller', 'Sound Engineer', 'Other' );
	$interest = array( 'Products', 'Dealership', 'OEM Partnership', 'Technical Information' );
	$issue    = array( 'Installation', 'System Tuning / Calibration', 'Fault / Troubleshooting', 'Warranty', 'Spare Parts', 'General' );
	$support_type = array( 'Technical Support', 'Maintenance & Service', 'System Upgrade', 'Training', 'Spare Parts', 'Warranty Claim', 'General Enquiry' );
	$subject_opts = array( 'General Enquiry', 'Sales / Quotation', 'Technical Support', 'Partnership / Dealership', 'Careers', 'Other' );

	return array(
		'consultation' => array(
			'title'   => 'Request a Consultation',
			'submit'  => 'Submit Enquiry',
			'subject' => 'Consultation',
			'consent' => true,
			'fields'  => array(
				array( 'name', 'Full Name', 'text', true, true, array(), 'Your full name' ),
				array( 'company', 'Company / Organization', 'text', true, true, array(), 'Your company' ),
				array( 'email', 'Email Address', 'email', true, true, array(), 'you@example.com' ),
				array( 'phone', 'Phone Number', 'tel', true, true, array(), 'Your phone number' ),
				array( 'country', 'Country', 'select', true, true, $country, 'Select your country' ),
				array( 'city', 'City', 'text', true, true, array(), 'Your city' ),
				array( 'project_type', 'Project Type', 'select', true, false, $sector, 'Select project type' ),
				array( 'message', 'Tell us about your project / requirements', 'textarea', true, false, array(), 'Provide as much detail as possible about your project...' ),
			),
		),
		'contact'      => array(
			'title'   => 'Send us a message',
			'submit'  => 'Send Message',
			'subject' => 'Contact',
			'consent' => true,
			'fields'  => array(
				array( 'name', 'Full Name', 'text', true, true, array(), 'Your full name' ),
				array( 'email', 'Email Address', 'email', true, true, array(), 'you@example.com' ),
				array( 'phone', 'Phone Number', 'tel', false, true, array(), 'Your phone number' ),
				array( 'subject', 'Subject', 'select', true, true, $subject_opts, 'Select a subject' ),
				array( 'message', 'Your Message', 'textarea', true, false, array(), 'How can we help you?' ),
			),
		),
		'quote'        => array(
			'title'   => 'Request a Quote',
			'submit'  => 'Request quote',
			'subject' => 'Quote',
			'fields'  => array(
				array( 'name', 'Full name', 'text', true, true ),
				array( 'company', 'Company', 'text', false, true ),
				array( 'email', 'Email', 'email', true, true ),
				array( 'phone', 'Phone', 'tel', false, true ),
				array( 'country', 'Country', 'select', true, true, $country ),
				array( 'brand', 'Brand', 'text', false, true ),
				array( 'product', 'Product', 'text', false, true ),
				array( 'model', 'Model', 'text', false, true ),
				array( 'quantity', 'Quantity', 'number', false, true ),
				array( 'application', 'Application', 'text', false, true ),
				array( 'message', 'Details', 'textarea', false, true ),
			),
		),
		'product'      => array(
			'title'   => 'Product Enquiry',
			'submit'  => 'Send enquiry',
			'subject' => 'Product Enquiry',
			'fields'  => array(
				array( 'name', 'Full name', 'text', true, true ),
				array( 'company', 'Company', 'text', false, true ),
				array( 'email', 'Email', 'email', true, true ),
				array( 'phone', 'Phone', 'tel', false, true ),
				array( 'country', 'Country', 'select', true, true, $country ),
				array( 'brand', 'Brand', 'text', false, true ),
				array( 'product', 'Product', 'text', false, true ),
				array( 'model', 'Model', 'text', false, true ),
				array( 'quantity', 'Quantity', 'number', false, true ),
				array( 'application', 'Application', 'text', false, true ),
				array( 'message', 'Your question', 'textarea', false, true ),
			),
		),
		'dealer'       => array(
			'title'   => 'Become a Dealer',
			'submit'  => 'Submit application',
			'subject' => 'Dealer Application',
			'fields'  => array(
				array( 'name', 'Contact person', 'text', true, true ),
				array( 'company', 'Company', 'text', true, true ),
				array( 'email', 'Email', 'email', true, true ),
				array( 'phone', 'Phone', 'tel', false, true ),
				array( 'country', 'Country', 'select', true, true, $country ),
				array( 'city', 'City', 'text', false, true ),
				array( 'website', 'Website', 'text', false, true ),
				array( 'business_type', 'Business type', 'select', false, true, $biztype ),
				array( 'current_brands', 'Brands currently represented', 'text', false, true ),
				array( 'markets_served', 'Markets served', 'text', false, true ),
				array( 'warehouse_capability', 'Warehousing / distribution capability', 'select', false, true, $yesno ),
				array( 'message', 'Tell us about your business', 'textarea', false, true ),
				array( 'sc_file', 'Company profile (optional PDF)', 'file', false, true ),
			),
		),
		'fane'         => array(
			'title'   => 'FANE Partnership',
			'submit'  => 'Send enquiry',
			'subject' => 'FANE Partnership',
			'fields'  => array(
				array( 'name', 'Full name', 'text', true, true ),
				array( 'company', 'Company', 'text', false, true ),
				array( 'email', 'Email', 'email', true, true ),
				array( 'phone', 'Phone', 'tel', false, true ),
				array( 'country', 'Country', 'select', true, true, $country ),
				array( 'organisation_type', 'Organisation type', 'select', false, true, $orgtype ),
				array( 'interest', 'Interested in', 'select', false, true, $interest ),
				array( 'message', 'Your requirements', 'textarea', false, true ),
			),
		),
		'support'      => array(
			'title'   => 'Request Support',
			'submit'  => 'Submit Request',
			'subject' => 'Support Request',
			'fields'  => array(
				array( 'name', 'Full Name', 'text', true, true, array(), 'Your full name' ),
				array( 'company', 'Company / Organization', 'text', false, true, array(), 'Your company' ),
				array( 'email', 'Email', 'email', true, true, array(), 'you@example.com' ),
				array( 'phone', 'Phone', 'tel', true, true, array(), 'Your phone number' ),
				array( 'country', 'Country', 'select', true, true, $country, 'Select your country' ),
				array( 'city', 'City', 'text', false, true, array(), 'Your city' ),
				array( 'model', 'Product / Model', 'text', true, true, array(), 'e.g. Yamaha DM7, d&b GSL' ),
				array( 'serial', 'Serial Number', 'text', false, true, array(), 'Enter serial number' ),
				array( 'support_type', 'Type of Support', 'select', true, false, $support_type, 'Select support type' ),
				array( 'message', 'Describe the Issue', 'textarea', true, false, array(), 'Provide as much detail as possible about the issue' ),
			),
		)
	);
}

/**
 * Render a single field.
 */
function sc_enq_field_html( $fld ) {
	$name = $fld[0];
	$label = $fld[1];
	$type = $fld[2];
	$req  = ( empty( $fld[3] ) === false );
	$full = ( empty( $fld[4] ) === false );
	$opts = isset( $fld[5] ) ? $fld[5] : array();
	$ph   = isset( $fld[6] ) ? (string) $fld[6] : '';

	$cls = 'sc-field' . ( $full ? '' : ' sc-field--full' );
	$id  = 'scf_' . $name;
	$reqattr = $req ? ' required' : '';
	$reqmark = $req ? ' <span class="req">*</span>' : '';
	$phattr  = ( strlen( $ph ) > 0 ) ? ' placeholder="' . esc_attr( $ph ) . '"' : '';

	$out  = '<div class="' . esc_attr( $cls ) . '">';
	$out .= '<label for="' . esc_attr( $id ) . '">' . esc_html( $label ) . $reqmark . '</label>';
	if ( 'textarea' === $type ) {
		$out .= '<textarea id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '"' . $reqattr . $phattr . '></textarea>';
	} elseif ( 'select' === $type ) {
		$ph_opt = ( strlen( $ph ) > 0 ) ? $ph : 'Select';
		$out .= '<select id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '"' . $reqattr . '>';
		$out .= '<option value="">' . esc_html( $ph_opt ) . '</option>';
		foreach ( $opts as $o ) {
			$out .= '<option value="' . esc_attr( $o ) . '">' . esc_html( $o ) . '</option>';
		}
		$out .= '</select>';
	} elseif ( 'file' === $type ) {
		$out .= '<input type="file" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">';
	} else {
		$out .= '<input type="' . esc_attr( $type ) . '" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '"' . $reqattr . $phattr . '>';
	}
	$out .= '</div>';
	return $out;
}

/**
 * Current page URL for the redirect-back target.
 */
function sc_enq_current_url() {
	$url = get_permalink();
	if ( ! $url ) {
		$url = home_url( '/' );
	}
	return $url;
}

/**
 * Render a full form of a given type.
 */
function sc_enq_render_form( $type, $title_override = '' ) {
	$forms = sc_enq_forms();
	$type  = $type ? $type : 'consultation';
	if ( ! isset( $forms[ $type ] ) ) {
		return '';
	}
	$f = $forms[ $type ];

	ob_start();
	echo '<div class="sc-form-wrap" id="sc-form">';

	if ( isset( $_GET['sc_sent'] ) ) {
		echo '<div class="sc-form__notice sc-form__notice--ok">Thank you. Your enquiry has been received and our team will respond shortly.</div>';
	} elseif ( isset( $_GET['sc_error'] ) ) {
		$e   = sanitize_key( wp_unslash( $_GET['sc_error'] ) );
		$msg = 'Something went wrong. Please try again.';
		if ( 'validation' === $e ) {
			$msg = 'Please complete all required fields with valid details.';
		} elseif ( 'rate' === $e ) {
			$msg = 'Too many submissions. Please try again shortly.';
		} elseif ( 'spam' === $e ) {
			$msg = 'Your submission could not be processed. Please try again.';
		}
		echo '<div class="sc-form__notice sc-form__notice--err">' . esc_html( $msg ) . '</div>';
	}
	?>
	<form class="sc-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" enctype="multipart/form-data">
		<input type="hidden" name="action" value="sc_enquiry">
		<input type="hidden" name="sc_type" value="<?php echo esc_attr( $type ); ?>">
		<input type="hidden" name="sc_rendered" value="<?php echo esc_attr( time() ); ?>">
		<input type="hidden" name="sc_redirect" value="<?php echo esc_url( sc_enq_current_url() ); ?>">
		<?php wp_nonce_field( 'sc_enquiry_submit', 'sc_nonce' ); ?>
		<div class="sc-form__hp"><label>Leave this field empty <input type="text" name="sc_website" tabindex="-1" autocomplete="off"></label></div>
		<div class="sc-form__grid">
			<?php
			foreach ( $f['fields'] as $fld ) {
				echo sc_enq_field_html( $fld ); // Escaped inside builder.
			}
			?>
		</div>
		<?php if ( ! empty( $f['consent'] ) ) : ?>
		<div class="sc-form__consent">
			<label><input type="checkbox" name="sc_consent" value="1" required> <span><?php
				printf(
					/* translators: 1: privacy policy link, 2: terms and conditions link */
					esc_html__( 'I agree to the %1$s and %2$s.', 'sound-creations-enquiries' ),
					'<a href="' . esc_url( home_url( '/privacy-policy/' ) ) . '">' . esc_html__( 'Privacy Policy', 'sound-creations-enquiries' ) . '</a>',
					'<a href="' . esc_url( home_url( '/terms/' ) ) . '">' . esc_html__( 'Terms & Conditions', 'sound-creations-enquiries' ) . '</a>'
				);
			?></span></label>
		</div>
		<?php endif; ?>
		<div class="sc-form__actions">
			<button class="sc-btn sc-btn--primary sc-form__submit" type="submit"><?php echo esc_html( $f['submit'] ); ?> <span class="sc-btn__arrow" aria-hidden="true">&rarr;</span></button>
			<span class="sc-form__note"><svg class="sc-form__note-ico" viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg> We respect your privacy. Your information will never be shared.</span>
		</div>
	</form>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Shortcode: [sc_enquiry_form type="dealer"].
 */
add_shortcode(
	'sc_enquiry_form',
	function ( $atts ) {
		$a = shortcode_atts( array( 'type' => 'consultation', 'title' => '' ), $atts );
		return sc_enq_render_form( sanitize_key( $a['type'] ), $a['title'] );
	}
);

/**
 * Map page slugs to form types for auto-append.
 */
function sc_enq_slug_map() {
	return array(
		'request-a-consultation' => 'consultation',
		'request-a-quote'        => 'quote',
		'become-a-dealer'        => 'dealer',
		'support'                => 'support',
		'contact'                => 'contact',
	);
}

/**
 * Auto-append the correct form on the mapped enquiry pages.
 */
add_filter(
	'the_content',
	function ( $content ) {
		if ( is_singular( 'page' ) && in_the_loop() && is_main_query() ) {
			$slug = get_post_field( 'post_name', get_the_ID() );
			$map  = sc_enq_slug_map();
			if ( isset( $map[ $slug ] ) && false === strpos( $content, 'sc-form' ) ) {
				$content .= sc_enq_render_form( $map[ $slug ] );
			}
		}
		return $content;
	},
	20
);

/**
 * Enqueue the form stylesheet.
 */
add_action(
	'wp_enqueue_scripts',
	function () {
		wp_register_style( 'sc-enquiries', SC_ENQ_URI . 'assets/forms.css', array( 'sc-main' ), SC_ENQ_VERSION );
		wp_enqueue_style( 'sc-enquiries' );
	},
	30
);
