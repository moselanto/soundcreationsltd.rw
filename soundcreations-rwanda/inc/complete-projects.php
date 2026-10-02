<?php
/**
 * Complete Projects: completed jobs from the SCL RW Company Profile 2025,
 * grouped by sector (no counts shown), on the Projects page under the case studies.
 * A sector list on the left switches the projects shown on the right; on phones
 * the sectors become a swipeable row.
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

/** Sector key => one-line description shown above its projects. */
function scrw_complete_sector_notes() {
	return array(
		'worship'     => 'Clear speech, inspiring worship sound, stage lighting and screens for congregations of every size.',
		'public'      => 'Conferencing, public address and acoustics for ministries, embassies and corporate headquarters.',
		'hospitality' => 'Background music, DJ and live-performance sound for Kigali’s lounges, restaurants and clubs.',
		'hotels'      => 'Conference, banqueting and background audio systems for hotels and their event spaces.',
		'recreation'  => 'Powerful, dependable sound for event gardens, venues and fitness centres.',
		'rental'      => 'Equipment, system design and backup for the rental and event companies behind Kigali’s events.',
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
	$notes = scrw_complete_sector_notes();
	$cases = scrw_complete_case_studies();
	$first = (string) array_key_first( $data );
	ob_start();
	?>
	<section class="sc-section scrw-done" id="complete-projects" data-scrw-done>
		<div class="sc-container">
			<header class="scrw-done__head">
				<p class="sc-eyebrow"><?php esc_html_e( 'Complete Projects', 'soundcreations-rwanda' ); ?></p>
				<h2><?php esc_html_e( 'Trusted on projects across Rwanda.', 'soundcreations-rwanda' ); ?></h2>
				<p class="sc-lead"><?php esc_html_e( 'From churches and government to hotels, hospitality and the event companies behind Kigali’s biggest nights, these are some of the spaces our team has designed, supplied, installed and supports.', 'soundcreations-rwanda' ); ?></p>
			</header>

			<div class="scrw-done__layout">
				<div class="scrw-done__rail" role="tablist" aria-label="<?php esc_attr_e( 'Sectors', 'soundcreations-rwanda' ); ?>" aria-orientation="vertical">
					<?php foreach ( $data as $key => $sec ) : ?>
						<?php $on = ( $key === $first ); ?>
						<button type="button" class="scrw-done__tab<?php echo $on ? ' is-active' : ''; ?>" role="tab" id="scrw-tab-<?php echo esc_attr( $key ); ?>" aria-controls="scrw-panel-<?php echo esc_attr( $key ); ?>" aria-selected="<?php echo $on ? 'true' : 'false'; ?>" tabindex="<?php echo $on ? '0' : '-1'; ?>">
							<span class="scrw-done__icon" aria-hidden="true"><svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><?php echo $sec[1]; // phpcs:ignore -- static SVG. ?></svg></span>
							<span class="scrw-done__tab-label"><?php echo esc_html( $sec[0] ); ?></span>
							<svg class="scrw-done__chev" viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg>
						</button>
					<?php endforeach; ?>
				</div>

				<div class="scrw-done__panels">
					<?php foreach ( $data as $key => $sec ) : ?>
						<?php $on = ( $key === $first ); ?>
						<div class="scrw-done__panel<?php echo $on ? ' is-active' : ''; ?>" role="tabpanel" id="scrw-panel-<?php echo esc_attr( $key ); ?>" aria-labelledby="scrw-tab-<?php echo esc_attr( $key ); ?>" tabindex="0">
							<div class="scrw-done__panel-head">
								<span class="scrw-done__icon scrw-done__icon--lg" aria-hidden="true"><svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><?php echo $sec[1]; // phpcs:ignore -- static SVG. ?></svg></span>
								<div>
									<h3><?php echo esc_html( $sec[0] ); ?></h3>
									<?php if ( isset( $notes[ $key ] ) ) : ?><p><?php echo esc_html( $notes[ $key ] ); ?></p><?php endif; ?>
								</div>
							</div>
							<ul class="scrw-done__items">
								<?php foreach ( $sec[2] as $i => $name ) : ?>
									<?php if ( isset( $cases[ $name ] ) ) : ?>
										<li class="scrw-done__item scrw-done__item--case" style="--i:<?php echo (int) $i; ?>">
											<a href="<?php echo esc_url( home_url( $cases[ $name ] ) ); ?>">
												<span class="scrw-done__name"><?php echo esc_html( $name ); ?></span>
												<span class="scrw-done__case"><?php esc_html_e( 'View case study', 'soundcreations-rwanda' ); ?> &rarr;</span>
											</a>
										</li>
									<?php else : ?>
										<li class="scrw-done__item" style="--i:<?php echo (int) $i; ?>"><span class="scrw-done__tick" aria-hidden="true"></span><span class="scrw-done__name"><?php echo esc_html( $name ); ?></span></li>
									<?php endif; ?>
								<?php endforeach; ?>
							</ul>
						</div>
					<?php endforeach; ?>
				</div>
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
		root.classList.add('is-js');
		var tabs=Array.prototype.slice.call(root.querySelectorAll('.scrw-done__tab')), panels=root.querySelectorAll('.scrw-done__panel');
		function show(i,focus){
			tabs.forEach(function(t,k){ var on=(k===i); t.classList.toggle('is-active',on); t.setAttribute('aria-selected',on?'true':'false'); t.tabIndex=on?0:-1; panels[k].classList.toggle('is-active',on); });
			if(focus){tabs[i].focus();}
			if(window.matchMedia('(max-width: 900px)').matches){ tabs[i].scrollIntoView({block:'nearest',inline:'center',behavior:'smooth'}); }
		}
		tabs.forEach(function(t,i){
			t.addEventListener('click',function(){show(i,false);});
			t.addEventListener('keydown',function(e){
				var n=tabs.length;
				if(e.key==='ArrowDown'||e.key==='ArrowRight'){e.preventDefault();show((i+1)%n,true);}
				else if(e.key==='ArrowUp'||e.key==='ArrowLeft'){e.preventDefault();show((i-1+n)%n,true);}
				else if(e.key==='Home'){e.preventDefault();show(0,true);}
				else if(e.key==='End'){e.preventDefault();show(n-1,true);}
			});
		});
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
