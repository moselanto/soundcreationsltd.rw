<?php
/**
 * Central business + content settings (Sound Creations -> Settings).
 * Writes to option 'soundcreations_settings', read by the theme via sc_setting().
 * Single source of truth for contact details AND homepage / About page copy.
 *
 * @package SoundCreationsCore
 */

if ( defined( 'ABSPATH' ) === false ) {
	exit;
}

function sc_core_settings_fields() {
	// key => array( 'Label', 'type' ). type: text | textarea | image | heading.
	return array(
		'__sec_business'       => array( 'Business details', 'heading' ),
		'company_name'         => array( 'Company name', 'text' ),
			'slogan'               => array( 'Slogan (footer)', 'text' ),
		'tagline'              => array( 'Tagline', 'text' ),
		'phone'                => array( 'Phone (display)', 'text' ),
		'phone_link'           => array( 'Phone (tel: digits, e.g. +254715754758)', 'text' ),
		'phone2'               => array( 'Second phone (display, optional)', 'text' ),
		'phone2_link'          => array( 'Second phone (tel: digits, optional)', 'text' ),
		'email'                => array( 'Email', 'text' ),
		'address'              => array( 'Address', 'text' ),
		'hours_week'           => array( 'Hours - weekdays', 'text' ),
		'hours_sat'            => array( 'Hours - Saturday', 'text' ),
		'regions'              => array( 'Regional presence line', 'text' ),
		'whatsapp'             => array( 'WhatsApp number (digits only, country code, no +)', 'text' ),
		'whatsapp_prefill'     => array( 'WhatsApp button: pre-filled greeting message', 'text' ),
		'facebook'             => array( 'Facebook URL', 'text' ),
		'x'                    => array( 'X (Twitter) URL', 'text' ),
		'linkedin'             => array( 'LinkedIn URL', 'text' ),
		'youtube'              => array( 'YouTube URL', 'text' ),
		'instagram'            => array( 'Instagram URL', 'text' ),
		'hero_video'           => array( 'Hero background video URL (MP4)', 'image' ),

		// Geo + branch data feeding LocalBusiness structured data.
		// A branch node is emitted ONLY when its address is filled in, so
		// leaving these blank is safe: it means no claim is made, rather
		// than a wrong one. Never enter a placeholder address here.
		'__sec_locations'      => array( 'Locations and local SEO', 'heading' ),
		'google_business'      => array( 'Google Business Profile URL', 'text' ),
		'geo_lat'              => array( 'Nairobi latitude (e.g. -1.2664)', 'text' ),
		'geo_lng'              => array( 'Nairobi longitude (e.g. 36.8065)', 'text' ),
		'branch_kigali_address'=> array( 'Kigali: street address (blank = no Kigali listing)', 'text' ),
		'branch_kigali_phone'  => array( 'Kigali: phone', 'text' ),
		'branch_kigali_lat'    => array( 'Kigali: latitude', 'text' ),
		'branch_kigali_lng'    => array( 'Kigali: longitude', 'text' ),
		'branch_drc_address'   => array( 'DR Congo: street address (blank = no DRC listing)', 'text' ),
		'branch_drc_phone'     => array( 'DR Congo: phone', 'text' ),
		'branch_drc_lat'       => array( 'DR Congo: latitude', 'text' ),
		'branch_drc_lng'       => array( 'DR Congo: longitude', 'text' ),
		'branch_dubai_address' => array( 'Dubai: street address (blank = no Dubai listing)', 'text' ),
		'branch_dubai_phone'   => array( 'Dubai: phone', 'text' ),
		'branch_dubai_lat'     => array( 'Dubai: latitude', 'text' ),
		'branch_dubai_lng'     => array( 'Dubai: longitude', 'text' ),

		'__sec_footer'         => array( 'Footer', 'heading' ),
		'footer_address'       => array( 'Contact: address (one line per row)', 'textarea' ),
		'footer_hours_label'   => array( 'Contact: open-hours heading', 'text' ),
		'footer_hours'         => array( 'Contact: open hours (one line per row)', 'textarea' ),

		'__sec_home'           => array( 'Homepage content', 'heading' ),
		'home_hero_title'      => array( 'Hero: headline', 'text' ),
		'home_whatwedo_title'  => array( 'What we do: heading', 'text' ),
		'home_whatwedo_lead'   => array( 'What we do: intro', 'textarea' ),
		'home_solutions_title' => array( 'Solutions: heading', 'text' ),
		'home_projects_title'  => array( 'Featured projects: heading', 'text' ),
		'home_stat1_num'       => array( 'Proof stat 1: number', 'text' ),
		'home_stat1_label'     => array( 'Proof stat 1: label', 'text' ),
		'home_stat2_num'       => array( 'Proof stat 2: number', 'text' ),
		'home_stat2_label'     => array( 'Proof stat 2: label', 'text' ),
		'home_stat2_note'      => array( 'Proof stat 2: sub-note', 'text' ),
		'home_stat3_num'       => array( 'Proof stat 3: number', 'text' ),
		'home_stat3_label'     => array( 'Proof stat 3: label', 'text' ),
		'home_stat4_num'       => array( 'Proof stat 4: number', 'text' ),
		'home_stat4_label'     => array( 'Proof stat 4: label', 'text' ),
		'home_cta_title'       => array( 'CTA: heading', 'text' ),
		'home_cta_text'        => array( 'CTA: text', 'wysiwyg' ),

			'home_whatwedo_eyebrow'=> array( 'What we do: eyebrow', 'text' ),
			'home_solutions_eyebrow'=> array( 'Solutions: eyebrow', 'text' ),
			'home_partners_label'  => array( 'Partners strip: label', 'text' ),
			'home_projects_eyebrow'=> array( 'Featured projects: eyebrow', 'text' ),
		'__sec_home_images'    => array( 'Homepage images', 'heading' ),
		'home_hero_poster'     => array( 'Hero: poster image (shown before video loads)', 'image' ),
		'home_svc1_img'        => array( 'What we do - Consultancy: photo', 'image' ),
		'home_svc2_img'        => array( 'What we do - Distribution & Dealership: photo', 'image' ),
		'home_svc3_img'        => array( 'What we do - Integration: photo', 'image' ),
		'home_svc4_img'        => array( 'What we do - After-Sale Services: photo', 'image' ),
		'home_sol1_img'        => array( 'Solutions - Professional Audio: photo', 'image' ),
		'home_sol2_img'        => array( 'Solutions - Acoustics: photo', 'image' ),
		'home_sol3_img'        => array( 'Solutions - Sound & Acoustic Integration: photo', 'image' ),
		'home_cta_image'       => array( 'Closing CTA band: background photo', 'image' ),

		'__sec_about'          => array( 'About page content', 'heading' ),
		'about_hero_eyebrow'   => array( 'About hero: eyebrow', 'text' ),
		'about_hero_title'     => array( 'About hero: headline', 'text' ),
		'about_journey_p1'     => array( 'Journey: paragraph 1', 'wysiwyg' ),
			'about_hero_image'     => array( 'About hero: photo (upload to Media, paste URL)', 'image' ),
		// Our Work Process, the four-step row that replaced the "What We Do" grid on
		// the About page. Deliberately NOT reusing about_exp_eyebrow: that key has a
		// default in sc_default_settings() and was written into the saved option by
		// the one-shot prefill pass, and sc_setting() prefers a stored value, so new
		// copy on it would have been silently overridden by the stored "What We Do".
		'about_process_eyebrow' => array( 'Work process: eyebrow', 'text' ),
		'about_process_items'  => array( 'Work process steps (one per line: Title | Description | Link path, e.g. /service/consultancy/)', 'textarea' ),
		'about_partners_eyebrow'=> array( 'Partners: eyebrow', 'text' ),
		'about_partners_title' => array( 'Partners: heading', 'text' ),

			'__sec_downloads'      => array( 'Company profiles (downloads)', 'heading' ),
			'profiles_eyebrow'     => array( 'Profiles: eyebrow', 'text' ),
			'company_profile_url'  => array( 'Company Profile: PDF URL (upload to Media, paste link)', 'image' ),
			'company_profile_desc' => array( 'Company Profile: description', 'wysiwyg' ),

			'__sec_solutions'      => array( 'Solutions page content', 'heading' ),
			'sol_hero_title'       => array( 'Solutions hero: headline', 'text' ),
			'sol_hero_lead'        => array( 'Solutions hero: intro', 'wysiwyg' ),
			'sol_cta_title'        => array( 'Solutions CTA: heading', 'text' ),
			'sol_cta_text'         => array( 'Solutions CTA: text', 'wysiwyg' ),

			'__sec_contact'        => array( 'Contact / About page content', 'heading' ),
			'contact_eyebrow'      => array( 'Contact hero: eyebrow', 'text' ),
			'contact_title'        => array( 'Contact hero: headline', 'text' ),
			'contact_lead'         => array( 'Contact hero: intro', 'wysiwyg' ),
			'__sec_projectspage'   => array( 'Projects page', 'heading' ),
			'projects_eyebrow'     => array( 'Projects hero: eyebrow', 'text' ),
			'projects_title'       => array( 'Projects hero: headline', 'text' ),
			'projects_lead'        => array( 'Projects hero: intro', 'wysiwyg' ),
			'proj_stats'           => array( 'Projects stats (one per line: Number | Label | Sub-note)', 'textarea' ),
			'projects_cta_title'   => array( 'Projects CTA: heading', 'text' ),
			'projects_cta_text'    => array( 'Projects CTA: text', 'wysiwyg' ),

			'__sec_fane_content'   => array( 'FANE page - content', 'heading' ),
			'fane_title'           => array( 'FANE hero: headline', 'text' ),
			'fane_lead'            => array( 'FANE hero: intro paragraph', 'wysiwyg' ),
			'fane_hero_image'      => array( 'FANE hero: photo (upload to Media, paste URL)', 'image' ),
			'fane_diff_title'      => array( 'FANE "difference" section: heading', 'text' ),
			'fane_diff_body'       => array( 'FANE "difference" section: text', 'wysiwyg' ),
			'fane_heritage_title'  => array( 'FANE heritage section: heading', 'text' ),
			'fane_products_url'    => array( 'FANE product-range button: link (path or URL)', 'text' ),
			'fane_apps_url'        => array( 'FANE applications button: link (path or URL)', 'text' ),
			'fane_cta_title'       => array( 'FANE bottom banner: heading', 'text' ),
			'fane_cta_text'        => array( 'FANE bottom banner: text', 'wysiwyg' ),
			'fane_catalogue_url'   => array( 'FANE catalogue PDF - the Download Catalogue button shows only when this is set (Select file to upload, or paste a link)', 'image' ),

				'__sec_fane'           => array( 'FANE page - social links', 'heading' ),
			'fane_social_title'    => array( 'FANE social bar: heading', 'text' ),
			'fane_social_text'     => array( 'FANE social bar: sub-text', 'textarea' ),
			'fane_facebook'        => array( 'FANE Facebook URL (blank = company Facebook)', 'text' ),
			'fane_instagram'       => array( 'FANE Instagram URL (blank = company Instagram)', 'text' ),
			'fane_youtube'         => array( 'FANE YouTube URL (blank = company YouTube)', 'text' ),
			'fane_tiktok'          => array( 'FANE TikTok URL', 'text' ),
			'fane_x'               => array( 'FANE X (Twitter) URL (blank = company X)', 'text' ),
			'fane_linkedin'        => array( 'FANE LinkedIn URL (blank = company LinkedIn)', 'text' ),
			'fane_whatsapp'        => array( 'FANE WhatsApp number, digits only (blank = company WhatsApp)', 'text' ),

			'__sec_resources'         => array( 'Resources / Videos page', 'heading' ),
			'resources_eyebrow'       => array( 'Resources hero: eyebrow', 'text' ),
			'resources_title'         => array( 'Resources hero: headline', 'text' ),
			'resources_lead'          => array( 'Resources hero: intro', 'wysiwyg' ),
			'resources_videos_title'  => array( 'Videos section: heading', 'text' ),
			'resources_grid_title'    => array( 'Downloads section: heading', 'text' ),
			'resources_cta_title'     => array( 'Resources CTA: heading', 'text' ),
			'resources_cta_text'      => array( 'Resources CTA: text', 'wysiwyg' ),
	);
}

add_action(
	'admin_menu',
	function () {
		add_menu_page( 'Sound Creations', 'Sound Creations', 'manage_options', 'sc-settings', 'sc_core_render_settings_page', 'dashicons-format-audio', 58 );
		add_submenu_page( 'sc-settings', 'Settings', 'Settings', 'manage_options', 'sc-settings', 'sc_core_render_settings_page' );
	}
);

add_action(
	'admin_init',
	function () {
		register_setting(
			'sc_settings_group',
			'soundcreations_settings',
			array(
				'type'              => 'array',
				'sanitize_callback' => 'sc_core_sanitize_settings',
				'default'           => array(),
			)
		);
	}
);

function sc_core_sanitize_settings( $input ) {
	$clean = array();
	foreach ( sc_core_settings_fields() as $key => $def ) {
		$type = isset( $def[1] ) ? $def[1] : 'text';
		if ( 'heading' === $type ) {
			continue;
		}
		if ( isset( $input[ $key ] ) === false ) {
			continue;
		}
		$val = trim( (string) $input[ $key ] );
		if ( in_array( $key, array( 'facebook', 'x', 'linkedin', 'youtube', 'instagram', 'fane_facebook', 'fane_x', 'fane_linkedin', 'fane_youtube', 'fane_instagram', 'fane_tiktok' ), true ) || 'image' === $type ) {
			$clean[ $key ] = esc_url_raw( $val );
		} elseif ( 'email' === $key ) {
			$clean[ $key ] = sanitize_email( $val );
		} elseif ( 'wysiwyg' === $type ) {
				$clean[ $key ] = wp_kses_post( $val );
			} elseif ( 'textarea' === $type ) {
			$clean[ $key ] = sanitize_textarea_field( $val );
		} else {
			$clean[ $key ] = sanitize_text_field( $val );
		}
	}
	return $clean;
}

function sc_core_render_settings_page() {
	if ( current_user_can( 'manage_options' ) === false ) {
		return;
	}
	$opts     = get_option( 'soundcreations_settings', array() );
	$defaults = function_exists( 'sc_default_settings' ) ? sc_default_settings() : array();
	?>
	<div class="wrap">
		<h1>Sound Creations - Central Settings</h1>
		<p>Everything here feeds the live site: header, footer, contact details, and the homepage and About page copy. Update once and every template follows. Leave a field blank to use the built-in default (shown as grey placeholder text).</p>
		<form method="post" action="options.php">
			<?php settings_fields( 'sc_settings_group' ); ?>
			<table class="form-table" role="presentation"><tbody>
			<?php
			foreach ( sc_core_settings_fields() as $key => $def ) {
				$label = isset( $def[0] ) ? $def[0] : $key;
				$type  = isset( $def[1] ) ? $def[1] : 'text';
				if ( 'heading' === $type ) {
					printf( '<tr><th colspan="2" style="padding:26px 0 0;"><h2 style="margin:0;border-bottom:1px solid #dcdcde;padding-bottom:6px;">%s</h2></th></tr>', esc_html( $label ) );
					continue;
				}
				$value       = isset( $opts[ $key ] ) ? $opts[ $key ] : '';
				$placeholder = isset( $defaults[ $key ] ) ? $defaults[ $key ] : '';
				if ( 'textarea' === $type ) {
					printf(
						'<tr><th scope="row"><label for="sc_%1$s">%2$s</label></th><td><textarea id="sc_%1$s" name="soundcreations_settings[%1$s]" rows="3" class="large-text" placeholder="%4$s">%3$s</textarea></td></tr>',
						esc_attr( $key ),
						esc_html( $label ),
						esc_textarea( $value ),
						esc_attr( $placeholder )
					);
				} elseif ( 'wysiwyg' === $type ) {
						echo '<tr><th scope="row"><label for="sc_' . esc_attr( $key ) . '">' . esc_html( $label ) . '</label></th><td>';
						if ( strlen( (string) $value ) === 0 && strlen( (string) $placeholder ) > 0 ) {
							echo '<p class="description" style="margin:0 0 6px;color:#646970;">Shown on the live page if left blank: ' . esc_html( wp_strip_all_tags( (string) $placeholder ) ) . '</p>';
						}
						wp_editor(
							(string) $value,
							'sc_' . $key,
							array(
								'textarea_name' => 'soundcreations_settings[' . $key . ']',
								'media_buttons' => false,
								'textarea_rows' => 6,
								'teeny'         => true,
								'quicktags'     => true,
							)
						);
						echo '</td></tr>';
					} elseif ( 'image' === $type ) {
					$sc_is_img = ( substr( $key, -4 ) === '_img' ) || in_array( $key, array( 'home_hero_poster', 'home_cta_image', 'about_hero_image', 'fane_hero_image' ), true );
					$sc_mtype  = $sc_is_img ? 'image' : '';
					$sc_btn    = $sc_is_img ? 'Select image' : 'Select file';
					$sc_prev   = '';
					if ( strlen( (string) $value ) > 0 ) {
						if ( preg_match( '/[.](jpe?g|png|webp|gif|svg|avif)([?].*)?$/i', $value ) === 1 ) {
							$sc_prev = '<img src="' . esc_url( $value ) . '" alt="" style="max-width:190px;height:auto;border-radius:8px;margin-top:8px;display:block;border:1px solid #dcdcde;">';
						} else {
							$sc_prev = '<code style="display:inline-block;margin-top:8px;word-break:break-all;">' . esc_html( $value ) . '</code>';
						}
					}
					printf(
						'<tr><th scope="row"><label for="sc_%1$s">%2$s</label></th><td><div class="sc-media-field" data-sc-media-type="%5$s"><input type="text" id="sc_%1$s" name="soundcreations_settings[%1$s]" value="%3$s" placeholder="%4$s" class="regular-text sc-media-url" style="width:26rem;max-width:100%%;"> <button type="button" class="button sc-media-pick">%6$s</button> <button type="button" class="button sc-media-clear">Remove</button><div class="sc-media-prev">%7$s</div></div></td></tr>',
						esc_attr( $key ),
						esc_html( $label ),
						esc_attr( $value ),
						esc_attr( $placeholder ),
						esc_attr( $sc_mtype ),
						esc_html( $sc_btn ),
						$sc_prev
					);
				} else {
					printf(
						'<tr><th scope="row"><label for="sc_%1$s">%2$s</label></th><td><input type="text" id="sc_%1$s" name="soundcreations_settings[%1$s]" value="%3$s" placeholder="%4$s" class="regular-text" style="width:34rem;max-width:100%%;"></td></tr>',
						esc_attr( $key ),
						esc_html( $label ),
						esc_attr( $value ),
						esc_attr( $placeholder )
					);
				}
			}
			?>
			</tbody></table>
			<?php submit_button( 'Save settings' ); ?>
		</form>
	</div>
	<?php
}


add_action(
	'admin_enqueue_scripts',
	function ( $hook ) {
		if ( 'toplevel_page_sc-settings' === $hook ) {
			wp_enqueue_media();
			wp_enqueue_script(
				'sc-admin-media',
				plugins_url( 'assets/admin-media.js', dirname( __DIR__ ) . '/sound-creations-core.php' ),
				array( 'jquery' ),
				'1.0.0',
				true
			);
		}
	}
);

/**
 * One-time: promote the built-in defaults into the saved settings so every field
 * on the Central Settings screen shows real, editable content instead of an
 * empty box with grey placeholder text.
 *
 * Output-safe by construction. sc_setting() already falls back to exactly these
 * strings, so writing them into the option cannot change a single pixel on the
 * live site -- it only makes the values visible and editable in wp-admin, which
 * is the whole point. The screen's "leave blank to use the default" behaviour
 * still works for anything cleared afterwards.
 *
 * Three guards, in order:
 *  - the key must be a real editable field on the screen (headings skipped), so
 *    stray defaults never become phantom rows;
 *  - the default must be non-empty, so no field is filled with invented copy;
 *  - any value the team has already typed always wins and is never overwritten.
 *
 * @return int Number of fields filled.
 */
function sc_core_prefill_settings_from_defaults() {
	if ( function_exists( 'sc_default_settings' ) === false ) {
		return 0;
	}
	$sc_defaults = sc_default_settings();
	if ( is_array( $sc_defaults ) === false ) {
		return 0;
	}
	$sc_fields = sc_core_settings_fields();
	$sc_opts   = get_option( 'soundcreations_settings', array() );
	if ( is_array( $sc_opts ) === false ) {
		$sc_opts = array();
	}
	$sc_filled = 0;
	foreach ( $sc_defaults as $sc_key => $sc_val ) {
		if ( isset( $sc_fields[ $sc_key ] ) === false ) {
			continue;
		}
		$sc_type = isset( $sc_fields[ $sc_key ][1] ) ? $sc_fields[ $sc_key ][1] : 'text';
		if ( 'heading' === $sc_type ) {
			continue;
		}
		if ( strlen( trim( (string) $sc_val ) ) === 0 ) {
			continue;
		}
		$sc_cur = isset( $sc_opts[ $sc_key ] ) ? (string) $sc_opts[ $sc_key ] : '';
		if ( strlen( trim( $sc_cur ) ) > 0 ) {
			continue;
		}
		$sc_opts[ $sc_key ] = $sc_val;
		$sc_filled++;
	}
	if ( $sc_filled > 0 ) {
		update_option( 'soundcreations_settings', $sc_opts );
	}
	return $sc_filled;
}

// Runs once, on its own flag, at priority 12 so it lands after the theme has
// registered sc_default_settings() and after the catalog seeder.
add_action(
	'admin_init',
	function () {
		if ( '1' === get_option( 'sc_core_settings_prefilled' ) ) {
			return;
		}
		if ( current_user_can( 'manage_options' ) === false ) {
			return;
		}
		if ( function_exists( 'sc_default_settings' ) === false ) {
			return;
		}
		sc_core_prefill_settings_from_defaults();
		update_option( 'sc_core_settings_prefilled', '1', false );
	},
	12
);
