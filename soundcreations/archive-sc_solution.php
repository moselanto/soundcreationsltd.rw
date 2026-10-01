<?php
/**
 * Solutions landing page (renders at /solutions/, the sc_solution archive).
 * The Our Solutions and Featured Projects sections mirror the homepage:
 * the same solution cards (shared home_sol*_img settings) and the same
 * live sc_project carousel. The hero is the compact style used on /brands/;
 * hero and CTA band copy remain editable in
 * Sound Creations -> Settings (Solutions page content).
 *
 * @package SoundCreations
 */

if ( defined( 'ABSPATH' ) === false ) {
	exit;
}
get_header();

$sc_sol_hero  = SC_THEME_URI . '/assets/img/solutions-hero.jpg';
$sc_cta_photo = SC_THEME_URI . '/assets/img/cta-building.webp';

?>

<section class="sc-support-hero sc-solutions-hero" style="background-image:url('<?php echo esc_url( $sc_sol_hero ); ?>');">
	<span class="sc-support-hero__scrim" aria-hidden="true"></span>
	<div class="sc-container sc-support-hero__inner">
		<nav class="sc-crumb" aria-label="Breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> <span aria-hidden="true">&rsaquo;</span> <span class="sc-crumb__cur"><?php esc_html_e( 'Solutions', 'soundcreations' ); ?></span></nav>
		<h1 class="sc-support-hero__title"><?php echo esc_html( sc_setting( 'sol_hero_title', 'Engineered solutions. Exceptional experiences.' ) ); ?></h1>
		<p class="sc-lead sc-support-hero__lead"><?php echo sc_rich_e( sc_setting( 'sol_hero_lead', 'We design, integrate and support professional audio, visual, lighting and acoustic solutions for every space, application and performance.' ) ); ?></p>
	</div>
</section>

<section class="sc-section">
	<div class="sc-container">
		<div class="sc-sechead">
			<div>
				<p class="sc-eyebrow"><?php esc_html_e( 'Our Solutions', 'soundcreations' ); ?></p>
			</div>
		</div>
		<div class="sc-solgrid">
			<?php
			$sc_sols = array(
				array( 'solution-db3.webp', 'Professional Audio', 'Powerful, intelligible and reliable sound systems designed around your room and application.', '/solutions/professional-audio/', 'home_sol1_img' ),
				array( 'solution-acoustics.jpg', 'Acoustics', 'Acoustics treated as an engineering discipline: measure, analyze, design, treat and verify for clear, intelligible sound.', '/solutions/acoustics/', 'home_sol2_img' ),
				array( 'solution-av-integration.jpg', 'Sound & Acoustic Integration', 'Professional sound and acoustic systems, installed, commissioned and calibrated by our technical team.', '/solutions/installation/', 'home_sol3_img' ),
			);
			foreach ( $sc_sols as $sc_so ) :
				$sc_scimg = sc_setting( $sc_so[4], SC_THEME_URI . '/assets/img/home/' . $sc_so[0] );
				?>
				<a class="sc-solcard" href="<?php echo esc_url( home_url( $sc_so[3] ) ); ?>">
					<span class="sc-solcard__img"><img src="<?php echo esc_url( $sc_scimg ); ?>" alt="<?php echo esc_attr( $sc_so[1] ); ?>" loading="lazy" decoding="async" width="640" height="440"></span>
					<span class="sc-solcard__body">
						<h3><?php echo esc_html( $sc_so[1] ); ?></h3>
						<p><?php echo esc_html( $sc_so[2] ); ?></p>
						<span class="sc-solcard__more"><?php esc_html_e( 'Explore solution', 'soundcreations' ); ?> &rarr;</span>
					</span>
				</a>
				<?php
			endforeach;
			?>
		</div>
	</div>
</section>

<section class="sc-section sc-section--surface">
	<div class="sc-container">
		<div class="sc-section__head">
			<div>
				<p class="sc-eyebrow"><?php esc_html_e( 'Featured Projects', 'soundcreations' ); ?></p>
				<h2 style="margin:.3rem 0 0;"><?php esc_html_e( 'Real spaces. Real results.', 'soundcreations' ); ?></h2>
			</div>
			<a class="sc-link-arrow" href="<?php echo esc_url( home_url( '/projects/' ) ); ?>"><?php esc_html_e( 'View All Projects', 'soundcreations' ); ?> &rarr;</a>
		</div>
		<div class="sc-carousel" data-sc-carousel>
			<button class="sc-carousel__btn sc-carousel__btn--prev" type="button" data-sc-prev aria-label="Previous">&lsaquo;</button>
			<div class="sc-carousel__track">
				<?php
				$sc_pq = new WP_Query(
					array(
						'post_type'      => 'sc_project',
						'post_status'    => 'publish',
						'posts_per_page' => 8,
						'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
						'no_found_rows'  => true,
					)
				);
				if ( $sc_pq->have_posts() ) :
					while ( $sc_pq->have_posts() ) :
						$sc_pq->the_post();
						$pid  = get_the_ID();
						$img = sc_project_card_image( $pid );
						$loc  = (string) get_post_meta( $pid, '_sc_location', true );
						?>
						<a class="sc-project" href="<?php the_permalink(); ?>"><span class="sc-project__media"><img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" loading="lazy" decoding="async" width="400" height="250"></span><h3 class="sc-project__title"><?php echo esc_html( get_the_title() ); ?></h3><?php if ( strlen( $loc ) > 0 ) : ?><p class="sc-project__loc"><?php echo esc_html( $loc ); ?></p><?php endif; ?></a>
						<?php
					endwhile;
					wp_reset_postdata();
				else :
					?>
					<a class="sc-project" href="<?php echo esc_url( home_url( '/projects/' ) ); ?>"><span class="sc-project__media"><img src="<?php echo esc_url( SC_THEME_URI . '/assets/img/projects/boardroom.jpg' ); ?>" alt="Corporate Boardroom" loading="lazy" decoding="async" width="400" height="250"></span><h3 class="sc-project__title">Corporate Boardroom</h3><p class="sc-project__loc">Nairobi, Kenya</p></a>
					<a class="sc-project" href="<?php echo esc_url( home_url( '/projects/' ) ); ?>"><span class="sc-project__media"><img src="<?php echo esc_url( SC_THEME_URI . '/assets/img/projects/worship.jpg' ); ?>" alt="House of Worship" loading="lazy" decoding="async" width="400" height="250"></span><h3 class="sc-project__title">House of Worship</h3><p class="sc-project__loc">Kigali, Rwanda</p></a>
					<a class="sc-project" href="<?php echo esc_url( home_url( '/projects/' ) ); ?>"><span class="sc-project__media"><img src="<?php echo esc_url( SC_THEME_URI . '/assets/img/projects/conference.jpg' ); ?>" alt="Conference Centre" loading="lazy" decoding="async" width="400" height="250"></span><h3 class="sc-project__title">Conference Centre</h3><p class="sc-project__loc">Dubai, UAE</p></a>
					<?php
				endif;
				?>
			</div>
			<button class="sc-carousel__btn sc-carousel__btn--next" type="button" data-sc-next aria-label="Next">&rsaquo;</button>
		</div>
		<script>
		(function(){
			var roots = document.querySelectorAll('[data-sc-carousel]');
			roots.forEach(function(root){
				var track = root.querySelector('.sc-carousel__track');
				if (track === null) { return; }
				function step(){
					var card = track.querySelector('.sc-project');
					if (card === null) { return 320; }
					return card.getBoundingClientRect().width + 20;
				}
				var prev = root.querySelector('[data-sc-prev]');
				var next = root.querySelector('[data-sc-next]');
				if (prev) { prev.addEventListener('click', function(){ track.scrollBy({ left: -step(), behavior: 'smooth' }); }); }
				if (next) { next.addEventListener('click', function(){ track.scrollBy({ left: step(), behavior: 'smooth' }); }); }
			});
		})();
		</script>
	</div>
</section>

<section class="sc-section">
	<div class="sc-container">
		<div class="sc-cta-band sc-cta-band--photo" style="background-image:url('<?php echo esc_url( $sc_cta_photo ); ?>');">
			<div class="sc-cta-band__inner">
				<h2><?php echo esc_html( sc_setting( 'sol_cta_title', 'Have a project in mind?' ) ); ?></h2>
				<p class="sc-lead" style="margin:0 0 1.5rem;"><?php echo sc_rich_e( sc_setting( 'sol_cta_text', 'Let’s design and deliver the right solution for your space and application.' ) ); ?></p>
				<a class="sc-btn sc-btn--primary" href="<?php echo esc_url( home_url( '/request-a-consultation/' ) ); ?>"><?php esc_html_e( 'Request a Consultation', 'soundcreations' ); ?></a>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
