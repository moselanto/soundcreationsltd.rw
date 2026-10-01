<?php
/**
 * Sample catalog seeder: starter Brands, Products and Projects.
 * Idempotent by slug. Unverified details are flagged [VERIFY] for the team to complete.
 *
 * @package SoundCreationsCore
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function sc_core_get_or_create_term( $name, $taxonomy ) {
	if ( ! taxonomy_exists( $taxonomy ) ) {
		return 0;
	}
	$term = get_term_by( 'name', $name, $taxonomy );
	if ( $term ) {
		return (int) $term->term_id;
	}
	$res = wp_insert_term( $name, $taxonomy );
	if ( is_wp_error( $res ) ) {
		return 0;
	}
	return (int) $res['term_id'];
}

/**
 * Find an already-seeded post by slug, including ones the team has deleted.
 *
 * get_page_by_path() alone is not enough: since WP 4.5 wp_trash_post() renames
 * post_name to "<slug>__trashed", so a bare-slug lookup returns nothing for
 * anything sitting in Trash. The seeder then concludes the row is missing and
 * inserts a brand-new published copy, resurrecting content the team removed on
 * purpose and leaving the deleted original in Trash beside it. Repeated
 * trash/restore cycles can also produce "__trashed-2" style names, which is why
 * this falls back to a LIKE query rather than one exact match.
 *
 * Published rows win over trashed ones so a live post is never shadowed by an
 * old deleted namesake.
 *
 * @param string $slug      Seed slug.
 * @param string $post_type Post type to search.
 * @return WP_Post|null
 */
function sc_core_find_seeded_post( $slug, $post_type ) {
	$found = get_page_by_path( $slug, OBJECT, $post_type );
	if ( $found ) {
		return $found;
	}
	global $wpdb;
	$sc_id = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT ID FROM {$wpdb->posts}
			 WHERE post_type = %s
			   AND ( post_name = %s OR post_name LIKE %s )
			 ORDER BY ( post_status = 'publish' ) DESC, ID ASC
			 LIMIT 1",
			$post_type,
			$slug,
			$wpdb->esc_like( $slug . '__trashed' ) . '%'
		)
	);
	if ( empty( $sc_id ) ) {
		return null;
	}
	$found = get_post( (int) $sc_id );
	return $found ? $found : null;
}

function sc_core_seed_catalog() {
	$report = array( 'brands' => 0, 'products' => 0, 'projects' => 0 );

	// Brands we represent. Order is driven by menu_order (editable in wp-admin via the
	// "Order" field on each Brand) so the /brands/ page is not hardcoded. Logo maps to a
	// PNG in the active theme at assets/img/brands/logos/{logo}.png; FANE has no supplied
	// logo yet and falls back to a styled wordmark. Tuple: slug, title, origin, category,
	// tagline, menu_order, logo.
	$brands = array(
		array( 'db-technologies', 'dB Technologies', 'Italy', 'Loudspeakers & Amplification', 'Active loudspeakers and amplification engineered in Italy.', 10, 'db-technologies', 'https://www.dbtechnologies.com/' ),
		array( 'fane', 'FANE', 'United Kingdom', 'Loudspeaker Components', 'Precision-engineered drivers and components, built in the UK since 1954.', 30, 'fane', 'https://www.fane-international.com/' ),
		array( 'shure', 'Shure', 'United States', 'Microphones & Wireless', 'Industry-leading microphones and wireless audio systems.', 40, 'shure', 'https://www.shure.com/' ),
		array( 'midas', 'Midas', 'United Kingdom', 'Mixing Consoles', 'Legendary digital and analogue mixing consoles trusted worldwide.', 50, 'midas', 'https://www.midasconsoles.com/' ),
		array( 'behringer', 'Behringer', 'Germany', 'Mixing & Amplification', 'Accessible professional audio, mixing and amplification.', 70, 'behringer', 'https://www.behringer.com/' ),
		array( 'rockfon', 'Rockfon', 'Denmark', 'Acoustic Ceilings', 'High-performance acoustic ceiling and wall solutions.', 80, 'rockfon', 'https://www.rockfon.com/' ),
		array( 'barrisol', 'Barrisol', 'France', 'Acoustic & Stretch Ceilings', 'Acoustic and stretch-ceiling systems for modern spaces.', 90, 'barrisol', 'https://www.barrisol.com/' ),
		array( 'sommer-cable', 'Sommer Cable', 'Germany', 'Cables & Infrastructure', 'Professional audio, video and signal cabling solutions.', 100, 'somer-cable', 'https://www.sommercable.com/' ),
		array( 'bose-professional', 'Bose Professional', 'United States', 'Loudspeakers & Conferencing', 'Professional loudspeakers, DSP and conferencing systems.', 120, 'bose-professional', 'https://boseprofessional.com/' ),
		array( 'aid', 'Aid', 'Singapore', 'Acoustic Panels & Ceilings', 'Acoustic panels, ceiling systems and soundproofing solutions.', 130, 'aid', 'https://aid.co/' ),
		array( 'asona', 'Asona', 'Netherlands', 'Acoustic Ceilings & Systems', 'Seamless acoustic ceiling and wall systems since 1978.', 140, 'asona', 'https://asona.com/' ),
		array( 'allen-heath', 'Allen & Heath', 'United Kingdom', 'Mixing Consoles', 'Professional analogue and digital mixing consoles.', 150, 'allen-heath', 'https://www.allen-heath.com/' ),
	);
	foreach ( $brands as $b ) {
		list( $slug, $title, $origin, $cat, $tag, $order, $logo, $website ) = $b;
		$existing = sc_core_find_seeded_post( $slug, 'sc_brand' );
		if ( $existing && 'trash' === $existing->post_status ) {
			continue; // Team deleted this on purpose; never resurrect it.
		}
				if ( $existing ) {
			// Upsert: keep any team-edited body, but sync order + meta.
			$id = (int) $existing->ID;
			wp_update_post(
				array(
					'ID'         => $id,
					'menu_order' => $order,
				)
			);
		} else {
			$content = $title . ' is among the professional brands represented by Sound Creations. [VERIFY] Confirm the brand description and distribution status before publishing.';
			$id      = wp_insert_post(
				array(
					'post_type'    => 'sc_brand',
					'post_status'  => 'publish',
					'post_title'   => $title,
					'post_name'    => $slug,
					'post_content' => $content,
					'menu_order'   => $order,
				)
			);
		}
		if ( $id && ! is_wp_error( $id ) ) {
			update_post_meta( $id, '_sc_origin', $origin );
			update_post_meta( $id, '_sc_category', $cat );
			update_post_meta( $id, '_sc_tagline', $tag );
			update_post_meta( $id, '_sc_logo', $logo );
			if ( '' === (string) get_post_meta( $id, '_sc_website', true ) ) {
				update_post_meta( $id, '_sc_website', $website );
			}
			$report['brands']++;
		}
	}

	// Brands we no longer represent: remove any previously seeded posts so they
	// drop off the /brands/ (Products) page and the homepage partners strip.
	$sc_retired_brands = array( 'nexo', 'biamp', 'chamsys' );
	foreach ( $sc_retired_brands as $sc_gone_slug ) {
		$sc_gone = get_page_by_path( $sc_gone_slug, OBJECT, 'sc_brand' );
		if ( $sc_gone ) {
			wp_delete_post( (int) $sc_gone->ID, true );
		}
	}

	// FANE products: model names verified from the brief; specs left for the team to add from datasheets.
	$cat_term  = sc_core_get_or_create_term( 'Loudspeaker Components', 'sc_product_category' );
	$fane_term = sc_core_get_or_create_term( 'FANE', 'sc_brand_tax' );
	$products  = array(
		array( 'fane-colossus-18xb', 'FANE Colossus 18XB', '18-inch subwoofer driver' ),
		array( 'fane-imperium-18xl', 'FANE Imperium 18XL', '18-inch low-frequency driver' ),
		array( 'fane-sovereign-15-600', 'FANE Sovereign 15-600', '15-inch low-mid driver' ),
		array( 'fane-sovereign-12-250tc', 'FANE Sovereign 12-250TC', '12-inch driver' ),
		array( 'fane-cd140', 'FANE CD140', 'Compression driver' ),
	);
	foreach ( $products as $p ) {
		list( $slug, $title, $kind ) = $p;
		if ( sc_core_find_seeded_post( $slug, 'sc_product' ) ) {
			continue; // Covers trashed copies too, so deletions stick.
		}
		$content = $kind . '. [VERIFY] Add the full product description and datasheet specifications from the FANE datasheet before publishing.';
		$id      = wp_insert_post(
			array(
				'post_type'    => 'sc_product',
				'post_status'  => 'publish',
				'post_title'   => $title,
				'post_name'    => $slug,
				'post_content' => $content,
			)
		);
		if ( $id && ! is_wp_error( $id ) ) {
			update_post_meta( $id, '_sc_brand_name', 'FANE' );
			update_post_meta( $id, '_sc_model', trim( str_replace( 'FANE ', '', $title ) ) );
			update_post_meta( $id, '_sc_availability', 'Available on indent' );
			if ( $cat_term ) {
				wp_set_object_terms( $id, array( $cat_term ), 'sc_product_category' );
			}
			if ( $fane_term ) {
				wp_set_object_terms( $id, array( $fane_term ), 'sc_brand_tax' );
			}
			$report['products']++;
		}
	}

	// Projects shown on the /projects/ archive. Order via menu_order (editable in wp-admin).
	// Tuple: slug, title, client, location, industry, category (filter), badge, solution, image, summary, menu_order.
	$projects = array(
		array( 'citam-buruburu', 'CITAM Buruburu', 'CITAM Buruburu', 'Nairobi, Kenya', 'Houses of Worship', 'Worship', 'Worship', 'Professional Audio', 'citam-buruburu', 'A full dB Technologies T-Series system - T12/T8 front-of-house, S30 subwoofers, IG1 under-balcony fills and FMX15 monitors - driven by an Allen & Heath Avantis console for clear, powerful worship sound.', 10 ),
		array( 'all-saints-cathedral', 'All Saints Cathedral', 'All Saints Cathedral', 'Nairobi, Kenya', 'Houses of Worship', 'Worship', 'Worship', 'Acoustics', 'all-saints-cathedral', 'Acoustic treatment and audio enhancement for one of Africa’s largest cathedrals.', 20 ),
	);
	$project_bodies = sc_core_project_bodies();
	foreach ( $projects as $pr ) {
		list( $slug, $title, $client, $loc, $industry, $cat, $badge, $sol, $img, $summary, $order ) = $pr;
		$body     = isset( $project_bodies[ $slug ] ) ? $project_bodies[ $slug ] : '';
		$existing = sc_core_find_seeded_post( $slug, 'sc_project' );
		if ( $existing && 'trash' === $existing->post_status ) {
			continue; // Team deleted this on purpose; never resurrect it.
		}
				if ( $existing ) {
			$id  = (int) $existing->ID;
			$upd = array( 'ID' => $id, 'menu_order' => $order );
			// Refresh the body only while it is still the seeded [VERIFY] stub, so real
			// case-study content reaches the live site without clobbering team edits.
			if ( '' !== $body && false !== strpos( (string) $existing->post_content, '[VERIFY]' ) ) {
				$upd['post_content'] = $body;
			}
			wp_update_post( $upd );
		} else {
			$content = ( '' !== $body ) ? $body : 'Sound Creations delivered a professional audio installation for ' . $client . '. [VERIFY] Confirm the scope, equipment supplied and completion date before publishing.';
			$id      = wp_insert_post(
				array(
					'post_type'    => 'sc_project',
					'post_status'  => 'publish',
					'post_title'   => $title,
					'post_name'    => $slug,
					'post_content' => $content,
					'menu_order'   => $order,
				)
			);
		}
		if ( $id && is_wp_error( $id ) === false ) {
			update_post_meta( $id, '_sc_client', $client );
			update_post_meta( $id, '_sc_location', $loc );
			update_post_meta( $id, '_sc_summary', $summary );
			update_post_meta( $id, '_sc_category', $cat );
			update_post_meta( $id, '_sc_badge', $badge );
			update_post_meta( $id, '_sc_solution', $sol );
			update_post_meta( $id, '_sc_image', $img );
			$it = sc_core_get_or_create_term( $industry, 'sc_industry' );
			if ( $it ) {
				wp_set_object_terms( $id, array( $it ), 'sc_industry' );
			}
			$lt = sc_core_get_or_create_term( $loc, 'sc_location' );
			if ( $lt ) {
				wp_set_object_terms( $id, array( $lt ), 'sc_location' );
			}
			$report['projects']++;
		}
	}

	// Services shown in the homepage "What we do" section, with single pages at
	// /service/{slug}/. Tuple: slug, title, image (relative to assets/img/), summary, order.
	$services = array(
		array( 'consultancy', 'Consultancy', 'solutions/consultation.jpg', 'Design and consultation across audio, acoustics, lighting and visuals - at every phase of your project.', 10 ),
		array( 'distribution-dealership', 'Distribution & Dealership', 'brands/partner.jpg', 'Certified exclusive dealers for leading global brands, with reliable regional distribution and logistics.', 20 ),
		array( 'integration', 'Integration', 'solutions/integration.jpg', 'Site mapping, system design, installation, commissioning, training and support for every audio and acoustic need.', 30 ),
		array( 'after-sale-services', 'After-Sale Services', 'support-hero.jpg', 'Warranty management, genuine spare parts, servicing and technical support that keep your systems performing.', 40 ),
	);
	if ( post_type_exists( 'sc_service' ) ) {
		$service_bodies = sc_core_service_bodies();
		foreach ( $services as $sv ) {
			list( $slug, $title, $img, $summary, $order ) = $sv;
			$body     = isset( $service_bodies[ $slug ] ) ? $service_bodies[ $slug ] : '';
			$existing = sc_core_find_seeded_post( $slug, 'sc_service' );
			if ( $existing && 'trash' === $existing->post_status ) {
				continue; // Team deleted this on purpose; never resurrect it.
			}
						if ( $existing ) {
				$id  = (int) $existing->ID;
				$upd = array( 'ID' => $id, 'menu_order' => $order );
				// Refresh the body only while it is still the seeded [VERIFY] stub.
				if ( '' !== $body && false !== strpos( (string) $existing->post_content, '[VERIFY]' ) ) {
					$upd['post_content'] = $body;
				}
				wp_update_post( $upd );
			} else {
				$content = ( '' !== $body ) ? $body : ( $title . ' service. [VERIFY] Confirm the description before publishing.' );
				$id      = wp_insert_post(
					array(
						'post_type'    => 'sc_service',
						'post_status'  => 'publish',
						'post_title'   => $title,
						'post_name'    => $slug,
						'post_content' => $content,
						'menu_order'   => $order,
					)
				);
			}
			if ( $id && is_wp_error( $id ) === false ) {
				update_post_meta( $id, '_sc_summary', $summary );
				update_post_meta( $id, '_sc_image', $img );
			}
		}
	}

	return $report;

}

/**
 * Full case-study bodies (HTML) for featured projects, sourced from Sound Creations'
 * own project write-ups. Keyed by project slug. Used by the seeder for new projects and
 * to replace the seeded [VERIFY] stub on existing projects (never overwrites team edits).
 */
function sc_core_project_bodies() {
	$citam = <<<'HTML'
<p>We were honoured to deliver a comprehensive audio solution at CITAM Buruburu, tailored to the demands of a dynamic worship environment with clarity, power and precision.</p>
<h2>Front of house</h2>
<p>A full-range dB Technologies T-Series system (T12 and T8) delivers powerful, consistent sound across the main auditorium.</p>
<h2>Subwoofers</h2>
<p>dB Technologies S30 dual 18-inch subwoofers handle the low end, delivering impactful bass for both speech and music.</p>
<h2>Under-balcony fills</h2>
<p>dB Technologies IG1 speakers maintain coverage and clarity in the under-balcony seating areas.</p>
<h2>Stage monitors</h2>
<p>dB Technologies FMX15 monitors provide clear, powerful foldback for the worship team.</p>
<h2>Audio control</h2>
<p>At the heart of the system, an Allen &amp; Heath Avantis digital mixing console offers flexible, intuitive control and reliable performance for both live and broadcast applications.</p>
<p>This installation reflects our commitment to delivering scalable, high-quality audio-visual systems that elevate the worship experience.</p>
HTML;

	$pcea = <<<'HTML'
<p>"We have good sound! I can't believe our church sound system could sound so good." Those were the words that met Sound Creations on the visit to certify the completed acoustic treatment at P.C.E.A. Chuka, in Meru.</p>
<h2>The challenge</h2>
<p>The Presbyterian Church of East Africa (PCEA) sanctuary in Chuka had suffered from poor acoustics for years. The single-level, T-shaped building was finished in hard materials - a terrazzo floor, natural-stone walls and the exposed underside of an iron-sheet roof on steel trusses - creating a highly reverberant space that destroyed speech clarity. Reverberation-time measurements recorded up to 3.77 seconds at 125 Hz and an average of 3.32 seconds at mid frequencies (500-1,000 Hz), far above the 1.22 seconds recommended for intelligibility in a space of that volume.</p>
<h2>Design and installation</h2>
<p>To bring the reverberation time down, Sound Creations installed 740 square metres of Rockfon Artic stone-wool acoustic ceiling panels, suspended a minimum of 200 mm below the existing ceiling and following its slope. Rockfon Artic combines high sound absorption with fire protection, thermal insulation, humidity and microorganism resistance, and excellent light reflection.</p>
<h2>The results</h2>
<p>Post-installation measurements confirmed the reverberation time at mid frequencies had dropped from 3.3 seconds to 1.1 seconds - exactly as predicted - transforming speech intelligibility. The church also gained improved aesthetics, better thermal comfort and a brighter, more light-filled sanctuary. The committee expressed great satisfaction with the results.</p>
HTML;

	return array(
		'citam-buruburu' => $citam,
		'pcea-chuka'     => $pcea,
	);
}

/**
 * Full body content (HTML) for the service pages, sourced from Sound Creations'
 * existing service pages. Keyed by service slug. Used to fill the [VERIFY] stub.
 */
function sc_core_service_bodies() {
	$consultancy = <<<'HTML'
<p>Consultation is a vital process to all of our solutions - not only for our project clientele but also for our everyday customers. Understanding the purpose of an application, the environment it will be used in and the capabilities of what we sell and install helps every client get the best from our solutions.</p>
<p>We provide a wide portfolio of design and consultation services across audio, lighting, visual and acoustic projects, at every phase of a project. Through experience we have learned that the consultation process is cheaper and easier on the client when it is carried out during the construction phase.</p>
<p>Our highly qualified team has extensive experience and meets expectations regardless of the scope of the project. Using high-end tools and software - including EASE and Rational Acoustics SMAART v8 - we measure, calculate and model the optimum solution for your space. Where needed we are backed by the design teams and engineers of the manufacturers we represent: an international network of consultants, designers and engineers ready to assist on complex projects.</p>
HTML;

	$distribution = <<<'HTML'
<p>With over 13 years of experience in the local and regional market, we have built deep product specialisation. Over this time we have been certified as the exclusive authorised dealer for a number of leading global brands.</p>
<p>Our distribution model sets us apart: we offer direct customer sales, retailer sales at friendly resale prices, and manufacturer-to-client sales. Reliable, expert shipping partners ensure goods reach the client in the best possible time and condition.</p>
<p>Our customer orientation, transparency and efficiency have made us the preferred choice for clients and manufacturers across Kenya, Rwanda, Tanzania, DRC Congo and the UAE.</p>
HTML;

	$integration = <<<'HTML'
<p>With a pool of experienced technical sound specialists, we offer professional site mapping, consultation, system design, installation, commissioning, training and support for all audio and acoustic needs - from conferencing systems to road trucks and home-theatre installations.</p>
<h2>Internal room acoustics and surface treatments</h2>
<p>Critical listening spaces - auditoria, churches, studios, theatres, conference rooms and home theatres, and any speech-intelligibility-sensitive space - benefit from accurate acoustic design. We provide the appropriate surface treatments to enhance the audio experience in these spaces.</p>
<h2>Sound isolation</h2>
<p>We provide acoustical measurement, analysis and design services to assure optimal acoustical isolation for existing or new construction.</p>
HTML;

	$aftersale = <<<'HTML'
<p>Our after-sale service keeps your systems performing long after handover. Warranty cover on the products we supply follows the manufacturer terms, which vary by product category.</p>
<h2>Warranty periods</h2>
<p>Power amplifiers, decoders, timing power supplies, mixers, active and passive speakers, electronic drums, electronic pianos and microphones are covered free of charge for one year from the date of purchase.</p>
<p>Accessory parts - speaker voice coils, diaphragms and high-frequency drivers, and microphone voice coils and diaphragms - are covered free of charge for three months from the date of purchase. Under contracts with a warranty period of three years or more, these accessories are covered free for one year from the date of purchase.</p>
<p>Brackets, welding wires, plugs, cables and cabinets are chargeable at the cost of materials and basic labour only. For theatres, government projects and other large projects, the warranty period is governed mainly by the signed contract.</p>
<h2>What is not covered</h2>
<p>Warranty service does not apply to damage caused by collision or burning arising from non-product-quality issues; unauthorised modification, disassembly or opening; improper installation, use or operation outside the instructions; self-assembly without official guidance; circuit modification or improper use of battery packs and chargers; or any damage from use not carried out according to the product instructions.</p>
<p>To make a claim, contact us at info@soundcreationsltd.com or +254 715 754 758 with your invoice number and a description of the fault.</p>
HTML;

	return array(
		'consultancy'             => $consultancy,
		'distribution-dealership' => $distribution,
		'integration'             => $integration,
		'after-sale-services'     => $aftersale,
	);
}

/**
 * One-time cleanup: remove the six generic placeholder projects that were
 * seeded onto /projects/ but are not Sound Creations work (PCEA Chuka,
 * Kabarak University, Nairobi Chapel, Hotel Audio Solution, Corporate
 * Boardroom, Live Event Production).
 *
 * Deliberately NOT part of sc_core_seed_catalog() and NOT gated on
 * SC_CORE_SEED_VERSION. Bumping that constant re-runs the entire seeder, and
 * its update path rewrites _sc_client, _sc_location, _sc_summary,
 * _sc_category, _sc_badge, _sc_solution and _sc_image unconditionally on
 * every already-seeded brand, product and project. That would clobber the
 * team's wp-admin edits -- CITAM Buruburu is filed under "Audio" on the live
 * site while its seed tuple still says "Worship", for one. So this runs on
 * its own one-shot flag and touches nothing else.
 *
 * Posts are trashed rather than force-deleted, so any real copy written into
 * them since seeding stays recoverable under Projects -> Trash.
 */
function sc_core_retire_placeholder_projects() {
	$sc_slugs = array(
		'pcea-chuka',
		'kabarak-university',
		'nairobi-chapel',
		'hotel-audio-solution',
		'corporate-boardroom',
		'live-event-production',
	);
	$sc_done = 0;
	foreach ( $sc_slugs as $sc_slug ) {
		$sc_post = sc_core_find_seeded_post( $sc_slug, 'sc_project' );
		if ( empty( $sc_post ) ) {
			continue;
		}
		if ( 'trash' === $sc_post->post_status ) {
			continue;
		}
		wp_trash_post( (int) $sc_post->ID );
		$sc_done++;
	}
	return $sc_done;
}

add_action(
	'admin_init',
	function () {
		if ( '1' === get_option( 'sc_core_retired_projects' ) ) {
			return;
		}
		if ( post_type_exists( 'sc_project' ) === false ) {
			return;
		}
		sc_core_retire_placeholder_projects();
		update_option( 'sc_core_retired_projects', '1', false );
	},
	11
);

// Auto re-run the (idempotent) seeder once after a plugin update so brand order,
// logos and new brands sync without visiting the Sample Catalog page manually.
add_action(
	'admin_init',
	function () {
		// Only ever move forward. An equal or higher stored version must skip the
		// seeder: its upsert path rewrites _sc_category, _sc_badge, _sc_summary and
		// friends on every existing row, undoing the team's wp-admin edits.
		$sc_seeded = (string) get_option( 'sc_core_seed_version' );
		if ( strlen( $sc_seeded ) > 0 && version_compare( $sc_seeded, SC_CORE_SEED_VERSION, '>=' ) ) {
			return;
		}
		if ( function_exists( 'sc_core_seed_catalog' ) ) {
			sc_core_seed_catalog();
			update_option( 'sc_core_seed_version', SC_CORE_SEED_VERSION );
		}
	}
);

add_action(
	'admin_menu',
	function () {
		add_submenu_page( 'sc-settings', 'Sample Catalog', 'Sample Catalog', 'manage_options', 'sc-seed-catalog', 'sc_core_render_seed_page' );
	},
	20
);

function sc_core_render_seed_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$rep = get_transient( 'sc_core_seed_report' );
	if ( $rep ) {
		delete_transient( 'sc_core_seed_report' );
	}
	?>
	<div class="wrap">
		<h1>Sample Catalog</h1>
		<p>Creates starter Brands, Products and Projects so those sections have real content. Idempotent - existing items (matched by slug) are skipped. Items flagged [VERIFY] are stubs for your team to confirm and complete.</p>
		<?php if ( is_array( $rep ) ) : ?>
			<div class="notice notice-success"><p>Added <?php echo (int) $rep['brands']; ?> brands, <?php echo (int) $rep['products']; ?> products and <?php echo (int) $rep['projects']; ?> projects. If any archive link shows 404, visit Settings -> Permalinks and click Save Changes once.</p></div>
		<?php endif; ?>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<input type="hidden" name="action" value="sc_core_seed_catalog">
			<?php wp_nonce_field( 'sc_core_seed_catalog' ); ?>
			<?php submit_button( 'Create sample catalog' ); ?>
		</form>
	</div>
	<?php
}

add_action(
	'admin_post_sc_core_seed_catalog',
	function () {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'Insufficient permissions.' );
		}
		check_admin_referer( 'sc_core_seed_catalog' );
		$rep = sc_core_seed_catalog();
		set_transient( 'sc_core_seed_report', $rep, 60 );
		wp_safe_redirect( admin_url( 'admin.php?page=sc-seed-catalog' ) );
		exit;
	}
);

/**
 * Never show the seeder's [VERIFY] editorial reminder to visitors.
 *
 * Seeded stubs carry a sentence such as "[VERIFY] Confirm the scope, equipment
 * supplied and completion date before publishing." That is a note to staff, but
 * it was published: a crawl found it live and visible to the public on
 * /projects/all-saints-cathedral/, directly beneath a real client name.
 *
 * This strips only the sentence containing the marker and leaves the rest of the
 * copy alone, so the surrounding text still reads normally. It filters display
 * output only -- the stored content is untouched and the reminder stays fully
 * visible in wp-admin, so the team can still see which entries need real copy.
 * No project detail is invented to fill the gap.
 *
 * @param string $content Post content.
 * @return string
 */
function sc_core_strip_verify_notes( $content ) {
	if ( is_admin() ) {
		return $content;
	}
	if ( is_string( $content ) === false ) {
		return $content;
	}
	if ( strpos( $content, '[VERIFY]' ) === false ) {
		return $content;
	}
	$sc_parts = preg_split( '/(?<=[.?' . chr( 33 ) . '])\s+/', $content );
	if ( is_array( $sc_parts ) === false ) {
		return $content;
	}
	$sc_keep = array();
	foreach ( $sc_parts as $sc_part ) {
		if ( strpos( $sc_part, '[VERIFY]' ) === false ) {
			$sc_keep[] = $sc_part;
		}
	}
	return trim( implode( ' ', $sc_keep ) );
}
add_filter( 'the_content', 'sc_core_strip_verify_notes', 9 );
