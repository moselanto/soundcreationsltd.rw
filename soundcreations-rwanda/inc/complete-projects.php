<?php
/**
 * Complete Projects: every completed job from the SCL RW Company Profile 2025,
 * grouped by sector, shown on the Projects page under the case studies.
 * Also available anywhere with the shortcode [scrw_complete_projects].
 *
 * To add a project: add the name to the right sector in scrw_complete_projects().
 * To link a name to its case study, add it to scrw_complete_case_studies().
 *
 * @package SoundCreationsRwanda
 */

if ( defined( 'ABSPATH' ) === false ) {
	exit;
}

/** Sector key => array( label, icon, list of completed projects ). */
function scrw_complete_projects() {
	return array(
		'worship'   => array(
			'Houses of Worship',
			'<path d="M12 2v5M9.5 4.5h5"/><path d="M5 22V12l7-5 7 5v10"/><path d="M10 22v-5a2 2 0 0 1 4 0v5"/>',
			array( 'Christian Life Assembly', 'New Life Bible Church', 'Bethesda Holy Church', 'Assemblies of God Kicukiro', 'Assemblies of God Nyamirambo', 'Foursquare City Light Church', 'Wells Salvation Church', 'Source of Deliverance Church', 'SDA Bilingual Church', 'SDA Bibare', 'Zion Temple Muhanga', 'Zion Temple Rubirizi', 'Christ Embassy Church', 'Calvary Wide Fellowship Ministries' ),
		),
		'public'    => array(
			'Public Institutions & Corporate',
			'<path d="M3 21h18"/><path d="M4 21V10l8-6 8 6v11"/><path d="M8 21v-7M12 21v-7M16 21v-7"/>',
			array( 'Ministry of Finance (MINECOFIN)', 'Rwanda Correctional Service Headquarters', 'Intare Conference Arena', 'Embassy of Sweden in Rwanda', 'Goethe-Institut Kigali', 'Afriprecast', 'Real Contractors Ltd', 'Tron Multi Services Ltd', 'Business Galax Ltd' ),
		),
		'hospitality' => array(
			'Bars, Restaurants & Clubs',
			'<path d="M8 22h8M12 15v7"/><path d="M5 3h14l-1.5 7a5.5 5.5 0 0 1-11 0z"/>',
			array( 'Atelier du Vin', 'Tiamo Lounge', 'Kivu Noir Lounge', 'Fuschia Cercle Sportif Kigali', 'Choose Kigali', 'Tania’s', 'Choma’d', 'The Wave', 'Crystal Lounge', 'Saga Bay', 'The Office Lounge', 'Kigali Lounge', 'Le Balcon', 'B-Lounge', 'Lemon Kigali', 'People Club', 'Shooters Club', 'Luxury Garden', 'Heroes', 'Illusion Club', 'Inferno Club', 'Maison Noire', 'Jollof Kigali', 'Déjà Vu', 'Kultura Fine Dining' ),
		),
		'hotels'    => array(
			'Hotels',
			'<path d="M3 21V7l9-4 9 4v14"/><path d="M3 21h18"/><path d="M9 21v-4h6v4"/><path d="M8 10h2M14 10h2M8 14h2M14 14h2"/>',
			array( 'Serena Hotel', 'Ubumwe Hotel', 'Grazia Hotel' ),
		),
		'recreation' => array(
			'Recreation & Sport',
			'<circle cx="12" cy="12" r="9"/><path d="M3.5 9h17M3.5 15h17M12 3a14 14 0 0 1 0 18M12 3a14 14 0 0 0 0 18"/>',
			array( 'Romantic Garden', 'Jalia Events Venue', 'Fitness Point Gym', 'Zone Fitness Gym' ),
		),
		'rental'    => array(
			'Rental & Event Companies',
			'<rect x="3" y="7" width="18" height="13" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><path d="M3 13h18"/>',
			array( 'Alfa Entertainment', 'Rwanda Events Company', 'Event Factory', 'No Limit Ltd', 'Gorilla Event', 'Bora Bora Sound', 'Z Sound', 'Yves Sound' ),
		),
	);
}

/** Project name => case study path on this site. */
function scrw_complete_case_studies() {
	return array(
		'Christian Life Assembly'         => '/projects/christian-life-assembly-church/',
		'Ministry of Finance (MINECOFIN)' => '/projects/minecofin-conference-system/',
		'Atelier du Vin'                  => '/projects/atelier-du-vin/',
		'Romantic Garden'                 => '/projects/romantic-garden/',
	);
}

function scrw_render_complete_projects() {
	$data  = scrw_complete_projects();
	$cases = scrw_complete_case_studies();
	$total = 0;
	foreach ( $data as $sec ) {
		$total += count( $sec[2] );
	}
	$show = 8; // Names shown before "Show all".
	ob_start();
	?>
	<section class="sc-section scrw-done" id="complete-projects" data-scrw-done>
		<div class="sc-container">
			<div class="scrw-done__head">
				<div>
					<p class="sc-eyebrow"><?php esc_html_e( 'Complete Projects', 'soundcreations-rwanda' ); ?></p>
					<h2><?php echo esc_html( sprintf( __( '%d completed projects across Rwanda.', 'soundcreations-rwanda' ), $total ) ); ?></h2>
					<p class="sc-lead"><?php esc_html_e( 'Sound, acoustics, lighting and conferencing delivered for churches, government, hospitality, hotels, venues and the rental companies behind Kigali’s events.', 'soundcreations-rwanda' ); ?></p>
				</div>
				<dl class="scrw-done__stats">
					<div><dt><?php echo (int) $total; ?></dt><dd><?php esc_html_e( 'Projects completed', 'soundcreations-rwanda' ); ?></dd></div>
					<div><dt><?php echo (int) count( $data ); ?></dt><dd><?php esc_html_e( 'Sectors served', 'soundcreations-rwanda' ); ?></dd></div>
					<div><dt><?php echo (int) count( $data['worship'][2] ); ?></dt><dd><?php esc_html_e( 'Houses of worship', 'soundcreations-rwanda' ); ?></dd></div>
					<div><dt><?php echo (int) count( $data['hospitality'][2] ); ?></dt><dd><?php esc_html_e( 'Hospitality venues', 'soundcreations-rwanda' ); ?></dd></div>
				</dl>
			</div>

			<div class="scrw-done__tabs" role="group" aria-label="<?php esc_attr_e( 'Filter by sector', 'soundcreations-rwanda' ); ?>">
				<button type="button" class="scrw-done__tab is-active" data-sec=""><?php esc_html_e( 'All sectors', 'soundcreations-rwanda' ); ?><span><?php echo (int) $total; ?></span></button>
				<?php foreach ( $data as $key => $sec ) : ?>
					<button type="button" class="scrw-done__tab" data-sec="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $sec[0] ); ?><span><?php echo (int) count( $sec[2] ); ?></span></button>
				<?php endforeach; ?>
			</div>

			<div class="scrw-done__grid">
				<?php foreach ( $data as $key => $sec ) : ?>
					<?php $n = count( $sec[2] ); ?>
					<article class="scrw-done__card<?php echo $n > $show ? ' is-long' : ''; ?>" data-sec="<?php echo esc_attr( $key ); ?>">
						<header class="scrw-done__card-head">
							<span class="scrw-done__icon" aria-hidden="true"><svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><?php echo $sec[1]; // phpcs:ignore -- static SVG paths. ?></svg></span>
							<h3><?php echo esc_html( $sec[0] ); ?></h3>
							<span class="scrw-done__count"><?php echo (int) $n; ?></span>
						</header>
						<ul class="scrw-done__list">
							<?php foreach ( $sec[2] as $i => $name ) : ?>
								<li<?php echo $i >= $show ? ' class="is-extra"' : ''; ?>>
									<?php if ( isset( $cases[ $name ] ) ) : ?>
										<a href="<?php echo esc_url( home_url( $cases[ $name ] ) ); ?>"><?php echo esc_html( $name ); ?><span class="scrw-done__case"><?php esc_html_e( 'Case study', 'soundcreations-rwanda' ); ?> &rarr;</span></a>
									<?php else : ?>
										<span><?php echo esc_html( $name ); ?></span>
									<?php endif; ?>
								</li>
							<?php endforeach; ?>
						</ul>
						<?php if ( $n > $show ) : ?>
							<button type="button" class="scrw-done__more" aria-expanded="false" data-more="<?php echo esc_attr( sprintf( __( 'Show all %d', 'soundcreations-rwanda' ), $n ) ); ?>" data-less="<?php esc_attr_e( 'Show fewer', 'soundcreations-rwanda' ); ?>"><?php echo esc_html( sprintf( __( 'Show all %d', 'soundcreations-rwanda' ), $n ) ); ?></button>
						<?php endif; ?>
					</article>
				<?php endforeach; ?>
			</div>

			<div class="scrw-done__cta">
				<div>
					<h3><?php esc_html_e( 'Planning a similar project?', 'soundcreations-rwanda' ); ?></h3>
					<p><?php esc_html_e( 'Tell us about your venue. Our Kigali team will visit, recommend and quote the right system.', 'soundcreations-rwanda' ); ?></p>
				</div>
				<a class="sc-btn sc-btn--primary" href="<?php echo esc_url( home_url( '/request-a-consultation/' ) ); ?>"><?php esc_html_e( 'Request a consultation', 'soundcreations-rwanda' ); ?> &rarr;</a>
			</div>
		</div>
	</section>
	<script>
	(function(){
		var root=document.querySelector('[data-scrw-done]'); if(root===null){return;}
		var tabs=root.querySelectorAll('.scrw-done__tab'), cards=root.querySelectorAll('.scrw-done__card');
		Array.prototype.forEach.call(tabs,function(t){ t.addEventListener('click',function(){
			Array.prototype.forEach.call(tabs,function(x){x.classList.remove('is-active');}); t.classList.add('is-active');
			var s=t.getAttribute('data-sec');
			Array.prototype.forEach.call(cards,function(c){ var on=(s===''||c.getAttribute('data-sec')===s); c.hidden=(on===false); if(s!==''&&on){c.classList.add('is-open');} });
			root.classList.toggle('is-single',s!=='');
		}); });
		Array.prototype.forEach.call(root.querySelectorAll('.scrw-done__more'),function(b){ b.addEventListener('click',function(){
			var c=b.closest('.scrw-done__card'), open=c.classList.toggle('is-open');
			b.setAttribute('aria-expanded',open?'true':'false'); b.textContent=open?b.getAttribute('data-less'):b.getAttribute('data-more');
		}); });
	})();
	</script>
	<?php
	return (string) ob_get_clean();
}

add_shortcode( 'scrw_complete_projects', 'scrw_render_complete_projects' );
add_action(
	'sc_projects_after_grid',
	function () {
		echo scrw_render_complete_projects(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
);
