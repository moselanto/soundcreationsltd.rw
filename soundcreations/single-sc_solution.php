<?php
/**
 * Single solution (Professional Audio, Acoustics, Sound & Acoustic Integration, ...).
 *
 * Professional, articulated layout that shares the service design system:
 * hero band, narrative prose, a sticky sidebar with the inclusions / outcome /
 * applications, related projects, and a closing CTA. Each real solution kind
 * gets a tailored section: Acoustics = Architectural Acoustic Services
 * (accordion + video + photos); Professional Audio = Capabilities cards +
 * deployment photos; Integration = turnkey installation context + process +
 * photos.
 *
 * @package SoundCreations
 */

if ( defined( 'ABSPATH' ) === false ) {
	exit;
}
get_header();

while ( have_posts() ) :
	the_post();

	$sc_summary  = (string) sc_field( 'summary' );
	$sc_includes = (string) sc_render_ticklist( sc_field( 'includes' ) );
	$sc_outcome  = (string) sc_field( 'outcome' );
	$sc_apps     = (string) sc_term_tags( get_the_ID(), 'sc_application' );
	$sc_aside_has = ( strlen( $sc_includes ) > 0 || strlen( $sc_outcome ) > 0 || strlen( $sc_apps ) > 0 );
	$sc_eyebrow  = sc_primary_term_name( get_the_ID(), 'sc_solution_area', __( 'Solution', 'soundcreations' ) );

	$sc_tl = strtolower( (string) get_the_title() );
	$sc_sk = 'general';
	if ( is_int( strpos( $sc_tl, 'acoustic' ) ) ) {
		$sc_sk = 'acoustics';
	} elseif ( is_int( strpos( $sc_tl, 'integ' ) ) || is_int( strpos( $sc_tl, 'visual' ) ) || is_int( strpos( $sc_tl, 'install' ) ) ) {
		$sc_sk = 'integration';
	} elseif ( is_int( strpos( $sc_tl, 'audio' ) ) ) {
		$sc_sk = 'audio';
	}

	// Each real solution kind renders its own tailored section below, so the
	// generic in-column capabilities strip is intentionally left empty.
	$sc_capmap = array(
		'audio'       => array(),
		'acoustics'   => array(),
		'integration' => array(),
		'general'     => array(),
	);
	$sc_caps = isset( $sc_capmap[ $sc_sk ] ) ? $sc_capmap[ $sc_sk ] : array();
	$sc_leadmap = array(
		'audio'       => 'Live sound, worship and installed audio systems, engineered and supported end to end.',
		'acoustics'   => 'Acoustic design, measurement and treatment for spaces that sound exactly as intended.',
		'integration' => 'Complete sound and acoustic installations, delivered turnkey.',
		'general'     => '',
	);
	$sc_lead = strlen( $sc_summary ) > 0 ? $sc_summary : ( isset( $sc_leadmap[ $sc_sk ] ) ? $sc_leadmap[ $sc_sk ] : '' );
	$sc_raw_body = trim( wp_strip_all_tags( get_the_content() ) );
	$sc_has_body = ( '' !== $sc_raw_body && false === stripos( $sc_raw_body, 'CONTENT TO BE CONFIRMED' ) );
	if ( $sc_sk === 'audio' ) {
		$sc_has_body = false;
	}
	$sc_main_has = ( $sc_has_body || count( $sc_caps ) > 0 );
	$sc_body_mod = '';
	if ( $sc_main_has === true && $sc_aside_has === false ) {
		$sc_body_mod = ' sc-svc-body--wide';
	} elseif ( $sc_main_has === false && $sc_aside_has === true ) {
		$sc_body_mod = ' sc-svc-body--solo';
	}
	?>

	<article class="sc-svc">
		<?php
			$sc_hero_img = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'large' ) : '';
			if ( '' === (string) $sc_hero_img ) {
				// Professional Audio now uses the dB Technologies VIO line-array install
				// ("db 10", 2026-09-22 owner request), replacing audio-dbtech.jpg.
				// NOTE: this map is only the FALLBACK. has_post_thumbnail() above wins,
				// so if a Featured Image is set on the Professional Audio solution post
				// in wp-admin, that image renders and this file is never reached.
				$sc_heromap = array(
					'integration' => '/assets/img/solutions/installation.webp',
					'audio'       => '/assets/img/solutions/audio-db10.webp',
					'acoustics'   => '/assets/img/solutions/acoustics.jpg',
					'general'     => '/assets/img/solutions-hero.jpg',
				);
				$sc_hero_img = SC_THEME_URI . ( isset( $sc_heromap[ $sc_sk ] ) ? $sc_heromap[ $sc_sk ] : $sc_heromap[ 'general' ] );
			}
			?>
			<header class="sc-svc-hero">
				<div class="sc-container">
					<?php echo sc_breadcrumb( array( array( 'Home', home_url( '/' ) ), array( 'Solutions', get_post_type_archive_link( 'sc_solution' ) ), array( get_the_title(), '' ) ) ); ?>
					<div class="sc-svc-hero__grid">
						<div class="sc-svc-hero__text">
							<p class="sc-eyebrow"><?php echo esc_html( $sc_eyebrow ); ?></p>
							<h1 class="sc-svc-hero__title"><?php the_title(); ?></h1>
							<?php if ( strlen( $sc_lead ) > 0 ) : ?>
								<p class="sc-svc-hero__lead"><?php echo esc_html( $sc_lead ); ?></p>
							<?php endif; ?>
							<div class="sc-svc-hero__actions">
								<a class="sc-btn sc-btn--primary" href="<?php echo esc_url( home_url( '/request-a-consultation/' ) ); ?>"><?php esc_html_e( 'Request a Consultation', 'soundcreations' ); ?></a>
								<a class="sc-btn sc-btn--ghost" href="<?php echo esc_url( get_post_type_archive_link( 'sc_solution' ) ); ?>"><?php esc_html_e( 'All Solutions', 'soundcreations' ); ?></a>
							</div>
						</div>
						<div class="sc-svc-hero__media" style="background-image:url('<?php echo esc_url( $sc_hero_img ); ?>');" role="img" aria-label="<?php echo esc_attr( get_the_title() ); ?>"></div>
					</div>
				</div>
			</header>

		<?php if ( 'integration' === $sc_sk ) : ?>
			<section class="sc-section sc-section--tight sc-section--surface sc-solsec">
				<div class="sc-container">
					<div class="sc-solsec__head">
						<p class="sc-eyebrow"><?php esc_html_e( 'Installation and integration', 'soundcreations' ); ?></p>
						<h2 class="sc-svc-h2"><?php esc_html_e( 'Turnkey sound and acoustic integration', 'soundcreations' ); ?></h2>
						<p class="sc-solsec__intro">Sound Creations Ltd designs, supplies, installs and supports complete sound and acoustic systems. From a single boardroom to a full auditorium, we handle the entire project - site survey and system design, professional installation and cabling, calibration and commissioning, operator training and ongoing technical support - so your venue performs reliably from the first event.</p>
						<ul class="sc-chips"><li>Sound systems</li><li>Acoustic treatment</li><li>Installation &amp; cabling</li><li>Calibration &amp; commissioning</li><li>Control &amp; automation</li></ul>
					</div>
					<div class="sc-steps">
						<div class="sc-step"><span class="sc-step__n">01</span><h3>Site survey and design</h3><p>We map your venue and design a complete system around your space, application and budget.</p></div>
						<div class="sc-step"><span class="sc-step__n">02</span><h3>Supply and installation</h3><p>Professional installation, rigging and cabling using equipment from the global brands we represent.</p></div>
						<div class="sc-step"><span class="sc-step__n">03</span><h3>Calibration and commissioning</h3><p>Systems tuned and commissioned by our technical team so every seat looks and sounds right.</p></div>
						<div class="sc-step"><span class="sc-step__n">04</span><h3>Training and support</h3><p>Operator training, documentation and responsive after-sales support to keep it performing.</p></div>
					</div>
					<div class="sc-showcase">
						<figure class="sc-shot">
							<img src="<?php echo esc_url( SC_THEME_URI . '/assets/img/solutions/install-citam.jpg' ); ?>" alt="<?php esc_attr_e( 'Sound and acoustic installation in a large house of worship', 'soundcreations' ); ?>" loading="lazy" decoding="async">
							<figcaption><span><?php esc_html_e( 'House of worship: sound and acoustic integration', 'soundcreations' ); ?></span></figcaption>
						</figure>
						<figure class="sc-shot">
							<img src="<?php echo esc_url( SC_THEME_URI . '/assets/img/solutions/install-theatre.jpg' ); ?>" alt="<?php esc_attr_e( 'Auditorium sound and acoustic integration', 'soundcreations' ); ?>" loading="lazy" decoding="async">
							<figcaption><span><?php esc_html_e( 'Auditorium sound and acoustic integration', 'soundcreations' ); ?></span></figcaption>
						</figure>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<?php if ( $sc_main_has || $sc_aside_has ) : ?>
		<section class="sc-section sc-section--tight">
			<div class="sc-container sc-svc-body<?php echo $sc_body_mod; ?>">
				<?php if ( $sc_main_has ) : ?>
				<div class="sc-svc-main">
					<?php if ( $sc_has_body ) : ?><div class="sc-prose"><?php the_content(); ?></div><?php endif; ?>

					<?php if ( count( $sc_caps ) > 0 ) : ?>
						<div class="sc-svc-highlights">
							<h2 class="sc-svc-h2"><?php esc_html_e( 'Capabilities', 'soundcreations' ); ?></h2>
							<div class="sc-svc-cards">
								<?php foreach ( $sc_caps as $sc_c ) : ?>
									<div class="sc-svc-card">
										<span class="sc-svc-card__dot" aria-hidden="true"></span>
										<h3><?php echo esc_html( $sc_c[0] ); ?></h3>
										<p><?php echo esc_html( $sc_c[1] ); ?></p>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endif; ?>
				</div>
				<?php endif; ?>

				<?php if ( $sc_aside_has ) : ?>
				<aside class="sc-svc-aside">
					<div class="sc-svc-sidecard">
						<?php if ( strlen( $sc_includes ) > 0 ) : ?>
							<span class="sc-svc-sidecard__label"><?php esc_html_e( 'What is included', 'soundcreations' ); ?></span>
							<div class="sc-svc-sidecard__includes"><?php echo $sc_includes; ?></div>
						<?php endif; ?>
						<?php if ( strlen( $sc_outcome ) > 0 ) : ?>
							<div class="sc-svc-fact"><span class="sc-svc-fact__k"><?php esc_html_e( 'Outcome', 'soundcreations' ); ?></span><span class="sc-svc-fact__v"><?php echo esc_html( $sc_outcome ); ?></span></div>
						<?php endif; ?>
						<?php if ( strlen( $sc_apps ) > 0 ) : ?>
							<div class="sc-svc-fact"><span class="sc-svc-fact__k"><?php esc_html_e( 'Applications', 'soundcreations' ); ?></span><div class="sc-svc-tags"><?php echo $sc_apps; ?></div></div>
						<?php endif; ?>
					</div>
				</aside>
				<?php endif; ?>
			</div>
		</section>
		<?php endif; ?>

		<?php if ( 'acoustics' === $sc_sk ) : ?>
			<section class="sc-section sc-section--tight sc-section--surface sc-acoustics">
				<div class="sc-container">
					<div class="sc-acoustics__head">
						<p class="sc-eyebrow"><?php esc_html_e( 'Acoustic expertise', 'soundcreations' ); ?></p>
						<h2 class="sc-svc-h2"><?php esc_html_e( 'Architectural Acoustic Services', 'soundcreations' ); ?></h2>
						<p class="sc-acoustics__intro"><?php esc_html_e( 'From first concept to the finished room, we design, measure and treat critical listening spaces so they perform exactly as intended.', 'soundcreations' ); ?></p>
					</div>
					<div class="sc-acoustics__grid">
						<div class="sc-acc">
							<details class="sc-acc__item" open>
								<summary class="sc-acc__q"><span><?php esc_html_e( 'Design and Consulting', 'soundcreations' ); ?></span></summary>
								<div class="sc-acc__a"><p>In partnership with architects and architectural firms, we can provide innovative solutions and procedures towards creating excellence in acoustic and electroacoustic design and installation. We always look forward to participate in the collaborative design process.</p></div>
							</details>
							<details class="sc-acc__item">
								<summary class="sc-acc__q"><span><?php esc_html_e( 'Testing and Measurement', 'soundcreations' ); ?></span></summary>
								<div class="sc-acc__a"><p>Using advanced acoustic testing and measurement equipment, Sound Creations&rsquo; consultants can evaluate and analyze different spaces to develop acoustic criteria as well as evaluate their achievement with utmost accuracy.</p></div>
							</details>
							<details class="sc-acc__item">
								<summary class="sc-acc__q"><span><?php esc_html_e( 'Surface Treatments', 'soundcreations' ); ?></span></summary>
								<div class="sc-acc__a"><p>Critical listening spaces such as auditoria, churches, studios, theatres, conference rooms, home theaters and all speech intelligibility sensitive spaces benefit from accurate acoustic design. We provide appropriate surface treatments to enhance the audio experience in these spaces.</p></div>
							</details>
						</div>
						<div class="sc-acoustics__media">
							<div class="sc-embed">
								<iframe src="https://www.youtube-nocookie.com/embed/HtZTBWb701I?rel=0" title="<?php esc_attr_e( 'Sound Creations - Architectural Acoustics', 'soundcreations' ); ?>" loading="lazy" referrerpolicy="strict-origin-when-cross-origin" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
							</div>
							<div class="sc-acoustics__shots">
								<figure class="sc-acoustics__shot">
									<img src="<?php echo esc_url( SC_THEME_URI . '/assets/img/solutions/acoustics-design.jpg' ); ?>" alt="<?php esc_attr_e( 'Acoustic diffuser and panel design model', 'soundcreations' ); ?>" loading="lazy" decoding="async">
									<figcaption><?php esc_html_e( 'Acoustic design modelling', 'soundcreations' ); ?></figcaption>
								</figure>
								<figure class="sc-acoustics__shot">
									<img src="<?php echo esc_url( SC_THEME_URI . '/assets/img/solutions/acoustics-measure.jpg' ); ?>" alt="<?php esc_attr_e( 'On-site reverberation time measurement with an acoustic analyser', 'soundcreations' ); ?>" loading="lazy" decoding="async">
									<figcaption><?php esc_html_e( 'On-site testing and measurement', 'soundcreations' ); ?></figcaption>
								</figure>
							</div>
						</div>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<?php if ( 'audio' === $sc_sk ) : ?>
		<section class="sc-section sc-section--tight sc-section--surface sc-industries">
			<div class="sc-container">
				<div class="sc-solsec__head">
					<p class="sc-eyebrow"><?php esc_html_e( 'Industries we serve', 'soundcreations' ); ?></p>
				</div>
				<div class="sc-inds">
					<button class="sc-inds__nav sc-inds__nav--prev" type="button" aria-label="Scroll to previous industries">&larr;</button>
					<div class="sc-inds__track">
						<?php
						// Owner-requested set (2026-09-22): Retail and Sports & Fitness removed, and
						// the separate Restaurants & Bars card merged into Hospitality -- one card
						// now covers lobbies, ballrooms, restaurants, bars and outdoor areas.
						// Element [3] is a full filename rather than a bare slug, so a card can
						// point at a real photograph in any format (the merged Hospitality card
						// uses a real Sound Creations install shot) instead of being locked to
						// "<slug>.jpg".
						$sc_industries = array(
							array( 'Hospitality, Restaurants & Bars', 'An effortless guest experience', 'Warm, even coverage that keeps conversation easy and the energy right - consistent, refined audio across lobbies, ballrooms, restaurants, bars and outdoor areas, all controlled from one place.', 'hospitality-card.webp' ),
							array( 'Worship', 'Every word, every seat', 'Intelligible speech and full-range music for services of any style, engineered around your room and its acoustics.', 'worship.webp' ),
							array( 'Live Performance & Events', 'Built for the moment', 'Concert-grade line arrays, monitoring and control for productions that have to sound right the first time.', 'live.webp' ),
							array( 'Corporate & Conferencing', 'Heard, clearly', 'Networked microphones and loudspeakers for boardrooms and hybrid meetings where every voice has to land.', 'corporate.webp' ),
							array( 'Education', 'Clarity that carries', 'Reliable, easy-to-run sound for lecture halls, auditoriums and campus spaces, from the front row to the back.', 'education.webp' ),
						);
						foreach ( $sc_industries as $sc_ind ) :
						?>
						<article class="sc-ind">
							<span class="sc-ind__img" style="background-image:url('<?php echo esc_url( SC_THEME_URI . '/assets/img/industries/' . $sc_ind[3] ); ?>');"></span>
							<span class="sc-ind__scrim"></span>
							<span class="sc-ind__body">
								<span class="sc-ind__eyebrow"><?php echo esc_html( $sc_ind[0] ); ?></span>
								<h3 class="sc-ind__title"><?php echo esc_html( $sc_ind[1] ); ?></h3>
								<p class="sc-ind__desc"><?php echo esc_html( $sc_ind[2] ); ?></p>
							</span>
						</article>
						<?php
						endforeach;
						?>
					</div>
					<button class="sc-inds__nav sc-inds__nav--next" type="button" aria-label="Scroll to more industries">&rarr;</button>
					<script>
					(function(){
						var wrap = document.currentScript.closest('.sc-inds');
						if ( wrap ) {
							var track = wrap.querySelector('.sc-inds__track');
							var prev = wrap.querySelector('.sc-inds__nav--prev');
							var next = wrap.querySelector('.sc-inds__nav--next');
							var step = function(){ var c = track.querySelector('.sc-ind'); return c ? c.getBoundingClientRect().width + 18 : 300; };
							if ( prev ) { prev.addEventListener('click', function(){ track.scrollBy({ left: -step(), behavior: 'smooth' }); }); }
							if ( next ) { next.addEventListener('click', function(){ track.scrollBy({ left: step(), behavior: 'smooth' }); }); }
						}
					})();
					</script>
				</div>
			</div>
		</section>
		<?php endif; ?>


		<?php
		$sc_inds    = get_the_terms( get_the_ID(), 'sc_industry' );
		$sc_ind_ids = array();
		if ( $sc_inds && is_wp_error( $sc_inds ) === false ) {
			foreach ( $sc_inds as $sc_i ) {
				$sc_ind_ids[] = (int) $sc_i->term_id;
			}
		}
		if ( count( $sc_ind_ids ) > 0 ) {
			$sc_rp = new WP_Query(
				array(
					'post_type'      => 'sc_project',
					'posts_per_page' => 3,
					'no_found_rows'  => true,
					'tax_query'      => array( array( 'taxonomy' => 'sc_industry', 'field' => 'term_id', 'terms' => $sc_ind_ids ) ),
				)
			);
			if ( $sc_rp->have_posts() ) {
				echo '<section class="sc-section sc-section--tight sc-section--surface"><div class="sc-container"><div class="sc-svc-reach__head"><p class="sc-eyebrow">' . esc_html__( 'Proof', 'soundcreations' ) . '</p><h2 class="sc-svc-h2">' . esc_html__( 'Related projects', 'soundcreations' ) . '</h2></div><div class="sc-grid">';
				while ( $sc_rp->have_posts() ) {
					$sc_rp->the_post();
					echo sc_media_card( get_the_ID(), sc_field( 'client' ), sc_field( 'year' ) );
				}
				echo '</div></div></section>';
			}
			wp_reset_postdata();
		}
		?>

		<section class="sc-section sc-section--tight">
			<div class="sc-container">
				<div class="sc-cta-band sc-cta-band--photo" style="background-image:url('<?php echo esc_url( sc_setting( 'home_cta_image', SC_THEME_URI . '/assets/img/cta-building.webp' ) ); ?>');">
					<div class="sc-cta-band__inner">
						<h2><?php esc_html_e( 'Have a project in mind?', 'soundcreations' ); ?></h2>
						<p class="sc-lead" style="margin:0 0 .9rem;"><?php esc_html_e( 'Tell us about your space and application. Our technical team will help you specify the right system.', 'soundcreations' ); ?></p>
						<a class="sc-btn sc-btn--primary" href="<?php echo esc_url( home_url( '/request-a-consultation/' ) ); ?>"><?php esc_html_e( 'Request a Consultation', 'soundcreations' ); ?></a>
					</div>
				</div>
			</div>
		</section>
	</article>

	<?php
endwhile;
get_footer();
