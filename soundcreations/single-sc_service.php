<?php
/**
 * Single service (Consultancy, Distribution & Dealership, Integration, After-Sale Services).
 *
 * Professional, articulated layout: split hero (copy + photo), narrative prose,
 * a tailored "what we deliver" strip, a sticky sidebar with sibling services +
 * CTA, an optional distribution-footprint section, and a closing call to action.
 *
 * @package SoundCreations
 */

if ( defined( 'ABSPATH' ) === false ) {
	exit;
}
get_header();

while ( have_posts() ) :
	the_post();

	$sc_summary = (string) sc_field( 'summary' );
	$sc_imgk    = (string) sc_field( 'image' );
	$sc_img     = '';
	if ( has_post_thumbnail() ) {
		$sc_img = get_the_post_thumbnail_url( get_the_ID(), 'large' );
	} elseif ( strlen( $sc_imgk ) > 0 ) {
		$sc_img = SC_THEME_URI . '/assets/img/' . $sc_imgk;
	}

	// Tailor the highlights + footprint to the service, matched by title keyword
	// so it is robust to slug spelling ("intergration", "after-sale-services", etc.).
	$sc_tl   = strtolower( (string) get_the_title() );
	$sc_kind = 'general';
	if ( is_int( strpos( $sc_tl, 'consult' ) ) ) {
		$sc_kind = 'consultancy';
	} elseif ( is_int( strpos( $sc_tl, 'distribut' ) ) || is_int( strpos( $sc_tl, 'dealer' ) ) ) {
		$sc_kind = 'distribution';
	} elseif ( is_int( strpos( $sc_tl, 'integ' ) ) ) {
		$sc_kind = 'integration';
	} elseif ( is_int( strpos( $sc_tl, 'after' ) ) || is_int( strpos( $sc_tl, 'sale' ) ) || is_int( strpos( $sc_tl, 'support' ) ) ) {
		$sc_kind = 'aftersale';
	}

	// When no featured image / custom field is set, reuse the matching homepage
	// "What we do" card image so the card and the page hero always match.
	$sc_kind_imgs = array(
		'consultancy'  => 'home/service-consultancy.webp',
		'distribution' => 'home/service-distribution-showroom.webp',
		'integration'  => 'home/service-integration.webp',
		'aftersale'    => 'home/service-aftersale-rack.webp',
	);
	if ( strlen( $sc_img ) === 0 && isset( $sc_kind_imgs[ $sc_kind ] ) ) {
		$sc_img = SC_THEME_URI . '/assets/img/' . $sc_kind_imgs[ $sc_kind ];
	}

	$sc_high = array(
		'consultancy'  => array(
			array( 'Design across every discipline', 'Audio, acoustics, lighting and visuals - modelled in EASE and verified with Rational Acoustics SMAART v8.' ),
			array( 'Guidance at every phase', 'From concept and construction through installation, commissioning and handover.' ),
			array( 'Manufacturer-backed engineering', 'Direct access to the design teams and engineers of the global brands we represent.' ),
		),
		'distribution' => array(
			array( 'Exclusive authorised dealer', 'Certified exclusive dealer for a portfolio of leading global brands.' ),
			array( 'Flexible supply models', 'Direct-to-customer, trade pricing for resellers, and manufacturer-to-client supply.' ),
			array( 'Reliable regional logistics', 'Expert shipping partners deliver on time and in the best condition, across the region.' ),
		),
		'integration'  => array(
			array( 'Designed around your site', 'Site mapping and complete system design for every audio, visual and acoustic need.' ),
			array( 'Installed and commissioned', 'Professional installation, calibration and commissioning by our own technical team.' ),
			array( 'Trained and supported', 'Operator training, documentation and ongoing technical support after go-live.' ),
		),
		'aftersale'    => array(
			array( 'Warranty management', 'Official product warranty handling for every brand we supply.' ),
			array( 'Genuine spare parts', 'Authentic spares sourced directly from the manufacturer.' ),
			array( 'Servicing and support', 'Scheduled maintenance and responsive technical back-up that keep systems performing.' ),
		),
		'general'      => array(),
	);
	$sc_cards = isset( $sc_high[ $sc_kind ] ) ? $sc_high[ $sc_kind ] : array();
	?>

	<article class="sc-svc">
		<header class="sc-svc-hero">
			<div class="sc-container sc-svc-hero__grid<?php echo strlen( $sc_img ) === 0 ? ' is-solo' : ''; ?>">
				<div class="sc-svc-hero__text">
					<?php echo sc_breadcrumb( array( array( 'Home', home_url( '/' ) ), array( 'Services', home_url( '/#services' ) ), array( get_the_title(), '' ) ) ); ?>
					<p class="sc-eyebrow"><?php esc_html_e( 'Service', 'soundcreations' ); ?></p>
					<h1 class="sc-svc-hero__title"><?php the_title(); ?></h1>
					<?php if ( strlen( $sc_summary ) > 0 ) : ?>
						<p class="sc-svc-hero__lead"><?php echo esc_html( $sc_summary ); ?></p>
					<?php endif; ?>
					<div class="sc-svc-hero__actions">
						<a class="sc-btn sc-btn--primary" href="<?php echo esc_url( home_url( '/request-a-consultation/' ) ); ?>"><?php esc_html_e( 'Request a Consultation', 'soundcreations' ); ?></a>
						<a class="sc-btn sc-btn--ghost" href="<?php echo esc_url( home_url( '/solutions/' ) ); ?>"><?php esc_html_e( 'Explore Solutions', 'soundcreations' ); ?></a>
					</div>
				</div>
				<?php if ( strlen( $sc_img ) > 0 ) : ?>
					<div class="sc-svc-hero__media" style="background-image:url('<?php echo esc_url( $sc_img ); ?>');" role="img" aria-label="<?php echo esc_attr( get_the_title() ); ?>"></div>
				<?php endif; ?>
			</div>
		</header>

		<section class="sc-section sc-section--tight">
			<div class="sc-container sc-svc-body">
				<div class="sc-svc-main">
					<div class="sc-prose"><?php the_content(); ?></div>

					<?php if ( count( $sc_cards ) > 0 ) : ?>
						<div class="sc-svc-highlights">
							<h2 class="sc-svc-h2"><?php esc_html_e( 'What we deliver', 'soundcreations' ); ?></h2>
							<div class="sc-svc-cards">
								<?php foreach ( $sc_cards as $sc_c ) : ?>
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

				<aside class="sc-svc-aside">
					<div class="sc-svc-sidecard">
						<?php
						$sc_sib = new WP_Query(
							array(
								'post_type'      => 'sc_service',
								'post_status'    => 'publish',
								'posts_per_page' => -1,
								'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
								'no_found_rows'  => true,
								'post__not_in'   => array( get_the_ID() ),
							)
						);
						if ( $sc_sib->have_posts() ) :
							?>
							<span class="sc-svc-sidecard__label"><?php esc_html_e( 'Our services', 'soundcreations' ); ?></span>
							<ul class="sc-svc-siblinks">
								<?php
								while ( $sc_sib->have_posts() ) :
									$sc_sib->the_post();
									?>
									<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
									<?php
								endwhile;
								wp_reset_postdata();
								?>
							</ul>
						<?php endif; ?>
						<div class="sc-svc-sidecard__cta">
							<p class="sc-svc-sidecard__cue"><?php esc_html_e( 'Tell us about your project and our technical team will help you specify the right system.', 'soundcreations' ); ?></p>
							<a class="sc-btn sc-btn--primary" href="<?php echo esc_url( home_url( '/request-a-consultation/' ) ); ?>"><?php esc_html_e( 'Request a Consultation', 'soundcreations' ); ?></a>
							<a class="sc-svc-sidecard__link" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"><?php esc_html_e( 'Or contact us directly', 'soundcreations' ); ?> &rarr;</a>
						</div>
					</div>
				</aside>
			</div>
		</section>

		<?php
		if ( 'distribution' === $sc_kind ) :
			$sc_reach_map = '';
			$sc_map_rel   = 'assets/img/map-africa-asia.jpg';
			if ( file_exists( get_theme_file_path( $sc_map_rel ) ) ) {
				$sc_reach_map = get_theme_file_uri( $sc_map_rel );
			}
			$sc_regions = array(
				array( 'East Africa', 'Kenya (head office, Nairobi), Rwanda (Kigali) and DR Congo (Kinshasa) - sales, projects and support.' ),
				array( 'Middle East', 'Dubai, UAE - our sourcing and logistics hub for the region.' ),
				array( 'Asia', 'Manufacturer and supply partners across South and East Asia keep our pipeline direct and cost-effective.' ),
				array( 'Wider region', 'Distribution and professional-sound project delivery beyond our branch network.' ),
			);
			?>
			<section class="sc-section sc-svc-reach">
				<div class="sc-container">
					<div class="sc-svc-reach__head">
						<p class="sc-eyebrow"><?php esc_html_e( 'Distribution footprint', 'soundcreations' ); ?></p>
						<h2 class="sc-svc-h2"><?php esc_html_e( 'From Africa to Asia', 'soundcreations' ); ?></h2>
						<p class="sc-svc-reach__intro"><?php esc_html_e( 'We source directly from leading manufacturers in Asia and the Middle East and distribute across East Africa and the wider region, with expert logistics keeping goods moving reliably.', 'soundcreations' ); ?></p>
					</div>
					<div class="sc-svc-reach__grid">
						<div class="sc-svc-reach__map"<?php echo strlen( $sc_reach_map ) > 0 ? ' style="background-image:url(\'' . esc_url( $sc_reach_map ) . '\');"' : ''; ?> role="img" aria-label="<?php esc_attr_e( 'Distribution footprint across Africa and Asia', 'soundcreations' ); ?>"></div>
						<div class="sc-svc-reach__regions">
							<?php foreach ( $sc_regions as $sc_r ) : ?>
								<div class="sc-svc-reach__region">
									<h3><?php echo esc_html( $sc_r[0] ); ?></h3>
									<p><?php echo esc_html( $sc_r[1] ); ?></p>
								</div>
							<?php endforeach; ?>
						</div>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<section class="sc-section">
			<div class="sc-container">
				<div class="sc-cta-band sc-cta-band--photo" style="background-image:url('<?php echo esc_url( sc_setting( 'home_cta_image', SC_THEME_URI . '/assets/img/cta-building.jpg' ) ); ?>');">
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
