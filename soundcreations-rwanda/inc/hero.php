<?php
/**
 * Homepage hero: either a background VIDEO or a SLIDESHOW of up to 4 slides.
 *
 * Edit everything in wp-admin: Appearance -> Customize -> Homepage Hero.
 *  - Hero type: Slides or Video.
 *  - Video: upload an MP4 (keep it short, 10-30s, under ~10MB), plus a poster
 *    image shown while it loads. The text from Slide 1 sits over the video.
 *  - Slides 1-4: photo, small label, headline, short text, button text + link.
 *    A slide with no photo is skipped, so 3 slides = leave Slide 4's photo empty.
 *  - Seconds per slide.
 * Defaults use Rwanda project photos bundled with the theme.
 *
 * @package SoundCreationsRwanda
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Default slides (bundled photos). */
function scrw_hero_defaults() {
	$img = get_stylesheet_directory_uri() . '/assets/img/';
	return array(
		1 => array(
			'image'   => $img . 'solution-integration-rw.webp',
			'eyebrow' => 'Sound Creations Rwanda',
			'title'   => 'Professional sound, lighting and acoustics for Rwanda.',
			'text'    => 'Consultancy, genuine equipment, integration and after-sale support from our Kigali team.',
			'btn'     => 'Request a consultation',
			'url'     => '/request-a-consultation/',
		),
		2 => array(
			'image'   => $img . 'about-hero-rw.webp',
			'eyebrow' => 'Acoustics · Intare Kivu Arena, Rubavu',
			'title'   => 'Rooms engineered for clear speech and powerful music.',
			'text'    => 'We measure, design, treat and tune every space so every seat hears it right.',
			'btn'     => 'Explore acoustics',
			'url'     => '/solutions/acoustics/',
		),
		3 => array(
			'image'   => $img . 'projects/ntare-louisenlund-hall-hd.webp',
			'eyebrow' => 'Integration · Ntare Louisenlund School',
			'title'   => 'Complete AV for schools, churches and conference halls.',
			'text'    => 'Sound, lighting, video and conferencing, installed, commissioned and supported locally.',
			'btn'     => 'See our projects',
			'url'     => '/projects/',
		),
		4 => array(
			'image'   => $img . 'projects/atelier-du-vin-interior-hd.webp',
			'eyebrow' => 'Distribution · Genuine equipment',
			'title'   => 'Authorised Yamaha distributor in Rwanda.',
			'text'    => 'World-class brands with local stock, warranty and technical backup.',
			'btn'     => 'Browse products',
			'url'     => '/products/',
		),
	);
}

/** Customizer: Appearance -> Customize -> Homepage Hero. */
add_action(
	'customize_register',
	function ( $wpc ) {
		$wpc->add_section( 'scrw_hero', array( 'title' => __( 'Homepage Hero', 'soundcreations-rwanda' ), 'priority' => 25, 'description' => __( 'Choose a background video or a slideshow of up to 4 slides. A slide without a photo is skipped.', 'soundcreations-rwanda' ) ) );

		$wpc->add_setting( 'scrw_hero_mode', array( 'default' => 'slides', 'sanitize_callback' => function ( $v ) { return in_array( $v, array( 'slides', 'video' ), true ) ? $v : 'slides'; } ) );
		$wpc->add_control( 'scrw_hero_mode', array( 'label' => __( 'Hero type', 'soundcreations-rwanda' ), 'section' => 'scrw_hero', 'type' => 'radio', 'choices' => array( 'slides' => __( 'Slides (3-4 photos)', 'soundcreations-rwanda' ), 'video' => __( 'Background video', 'soundcreations-rwanda' ) ) ) );

		$wpc->add_setting( 'scrw_hero_interval', array( 'default' => 6, 'sanitize_callback' => 'absint' ) );
		$wpc->add_control( 'scrw_hero_interval', array( 'label' => __( 'Seconds per slide', 'soundcreations-rwanda' ), 'section' => 'scrw_hero', 'type' => 'number', 'input_attrs' => array( 'min' => 3, 'max' => 15 ) ) );

		$wpc->add_setting( 'scrw_hero_video', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
		$wpc->add_control( new WP_Customize_Upload_Control( $wpc, 'scrw_hero_video', array( 'label' => __( 'Video (MP4, 10-30s, under ~10MB)', 'soundcreations-rwanda' ), 'section' => 'scrw_hero', 'mime_type' => 'video' ) ) );
		$wpc->add_setting( 'scrw_hero_poster', array( 'default' => '', 'sanitize_callback' => 'esc_url_raw' ) );
		$wpc->add_control( new WP_Customize_Image_Control( $wpc, 'scrw_hero_poster', array( 'label' => __( 'Video poster image (shown while the video loads)', 'soundcreations-rwanda' ), 'section' => 'scrw_hero' ) ) );

		$def = scrw_hero_defaults();
		for ( $i = 1; $i <= 4; $i++ ) {
			$d = $def[ $i ];
			$wpc->add_setting( "scrw_hero_{$i}_image", array( 'default' => $d['image'], 'sanitize_callback' => 'esc_url_raw' ) );
			$wpc->add_control( new WP_Customize_Image_Control( $wpc, "scrw_hero_{$i}_image", array( 'label' => sprintf( __( 'Slide %d: photo (landscape, at least 1600px wide)', 'soundcreations-rwanda' ), $i ), 'section' => 'scrw_hero' ) ) );
			foreach ( array( 'eyebrow' => 'small label', 'title' => 'headline', 'text' => 'short text', 'btn' => 'button text', 'url' => 'button link' ) as $k => $lab ) {
				$wpc->add_setting( "scrw_hero_{$i}_{$k}", array( 'default' => $d[ $k ], 'sanitize_callback' => 'url' === $k ? 'esc_url_raw' : 'sanitize_text_field' ) );
				$wpc->add_control( "scrw_hero_{$i}_{$k}", array( 'label' => sprintf( 'Slide %d: %s', $i, $lab ), 'section' => 'scrw_hero', 'type' => 'text' === $k ? 'textarea' : 'text' ) );
			}
		}
	}
);

/** Slides with a photo, in order. */
function scrw_hero_slides() {
	$def = scrw_hero_defaults();
	$out = array();
	for ( $i = 1; $i <= 4; $i++ ) {
		$s = array();
		foreach ( $def[ $i ] as $k => $v ) {
			$s[ $k ] = (string) get_theme_mod( "scrw_hero_{$i}_{$k}", $v );
		}
		if ( '' !== trim( $s['image'] ) ) {
			$out[] = $s;
		}
	}
	return $out;
}

function scrw_hero_copy( $s, $first, $heading_tag ) {
	$href = $s['url'];
	if ( '' !== $href && '/' === $href[0] ) {
		$href = home_url( $href );
	}
	?>
	<div class="scrw-hero__copy">
		<?php if ( '' !== $s['eyebrow'] ) : ?><p class="scrw-hero__eyebrow"><?php echo esc_html( $s['eyebrow'] ); ?></p><?php endif; ?>
		<?php if ( '' !== $s['title'] ) : ?><<?php echo $heading_tag; ?> class="scrw-hero__title"><?php echo esc_html( $s['title'] ); ?></<?php echo $heading_tag; ?>><?php endif; // phpcs:ignore ?>
		<?php if ( '' !== $s['text'] ) : ?><p class="scrw-hero__text"><?php echo esc_html( $s['text'] ); ?></p><?php endif; ?>
		<div class="scrw-hero__cta">
			<?php if ( '' !== $s['btn'] && '' !== $href ) : ?><a class="scrw-hero__btn" href="<?php echo esc_url( $href ); ?>"><?php echo esc_html( $s['btn'] ); ?> <span aria-hidden="true">&rarr;</span></a><?php endif; ?>
			<?php if ( $first ) : ?><a class="scrw-hero__btn scrw-hero__btn--ghost" href="<?php echo esc_url( home_url( '/projects/' ) ); ?>"><?php esc_html_e( 'View our projects', 'soundcreations-rwanda' ); ?></a><?php endif; ?>
		</div>
	</div>
	<?php
}

add_action(
	'sc_home_hero',
	function () {
		$slides = scrw_hero_slides();
		$mode   = get_theme_mod( 'scrw_hero_mode', 'slides' );
		$video  = (string) get_theme_mod( 'scrw_hero_video', '' );
		if ( 'video' === $mode && '' !== $video ) {
			$poster = (string) get_theme_mod( 'scrw_hero_poster', '' );
			if ( '' === $poster && $slides ) {
				$poster = $slides[0]['image'];
			}
			$s = $slides ? $slides[0] : array_merge( scrw_hero_defaults()[1], array() );
			?>
			<section class="scrw-hero scrw-hero--video" aria-label="<?php esc_attr_e( 'Introduction', 'soundcreations-rwanda' ); ?>">
				<div class="scrw-hero__media">
					<img class="scrw-hero__img" src="<?php echo esc_url( $poster ); ?>" alt="" fetchpriority="high" decoding="async">
					<video class="scrw-hero__video" muted loop playsinline preload="none" poster="<?php echo esc_url( $poster ); ?>" data-src="<?php echo esc_url( $video ); ?>"></video>
				</div>
				<span class="scrw-hero__scrim" aria-hidden="true"></span>
				<div class="sc-container scrw-hero__inner">
					<div class="scrw-hero__slide is-active"><?php scrw_hero_copy( $s, true, 'h1' ); ?></div>
					<div class="scrw-hero__controls">
						<button type="button" class="scrw-hero__ctrl scrw-hero__pause" aria-label="<?php esc_attr_e( 'Pause video', 'soundcreations-rwanda' ); ?>" aria-pressed="false"><span class="scrw-i-pause" aria-hidden="true"></span></button>
					</div>
				</div>
			</section>
			<?php
			return;
		}
		if ( ! $slides ) {
			$slides = array( scrw_hero_defaults()[1] );
		}
		$n        = count( $slides );
		$interval = max( 3, min( 15, (int) get_theme_mod( 'scrw_hero_interval', 6 ) ) );
		?>
		<section class="scrw-hero scrw-hero--slides" aria-roledescription="carousel" aria-label="<?php esc_attr_e( 'Highlights', 'soundcreations-rwanda' ); ?>" data-interval="<?php echo (int) $interval * 1000; ?>" style="--scrw-int:<?php echo (int) $interval; ?>s">
			<div class="scrw-hero__media">
				<?php foreach ( $slides as $i => $s ) : ?>
					<img class="scrw-hero__img<?php echo 0 === $i ? ' is-active' : ''; ?>" src="<?php echo esc_url( $s['image'] ); ?>" alt="" decoding="async" <?php echo 0 === $i ? 'fetchpriority="high"' : 'loading="lazy"'; ?>>
				<?php endforeach; ?>
			</div>
			<span class="scrw-hero__scrim" aria-hidden="true"></span>
			<div class="sc-container scrw-hero__inner">
				<div class="scrw-hero__stage" aria-live="off">
					<?php foreach ( $slides as $i => $s ) : ?>
						<div class="scrw-hero__slide<?php echo 0 === $i ? ' is-active' : ''; ?>" role="group" aria-roledescription="slide" aria-label="<?php echo esc_attr( ( $i + 1 ) . ' / ' . $n ); ?>"<?php echo 0 === $i ? '' : ' aria-hidden="true"'; ?>>
							<?php scrw_hero_copy( $s, 0 === $i, 0 === $i ? 'h1' : 'h2' ); ?>
						</div>
					<?php endforeach; ?>
				</div>
				<?php if ( $n > 1 ) : ?>
					<div class="scrw-hero__controls">
						<div class="scrw-hero__dots" role="tablist" aria-label="<?php esc_attr_e( 'Choose slide', 'soundcreations-rwanda' ); ?>">
							<?php foreach ( $slides as $i => $s ) : ?>
								<button type="button" class="scrw-hero__dot<?php echo 0 === $i ? ' is-active' : ''; ?>" role="tab" aria-selected="<?php echo 0 === $i ? 'true' : 'false'; ?>" aria-label="<?php echo esc_attr( sprintf( __( 'Slide %1$d: %2$s', 'soundcreations-rwanda' ), $i + 1, $s['title'] ) ); ?>"><span class="scrw-hero__dot-n"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span><span class="scrw-hero__dot-bar"><i></i></span></button>
							<?php endforeach; ?>
						</div>
						<div class="scrw-hero__arrows">
							<button type="button" class="scrw-hero__ctrl scrw-hero__pause" aria-label="<?php esc_attr_e( 'Pause slideshow', 'soundcreations-rwanda' ); ?>" aria-pressed="false"><span class="scrw-i-pause" aria-hidden="true"></span></button>
							<button type="button" class="scrw-hero__ctrl scrw-hero__prev" aria-label="<?php esc_attr_e( 'Previous slide', 'soundcreations-rwanda' ); ?>"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M15 18l-6-6 6-6"/></svg></button>
							<button type="button" class="scrw-hero__ctrl scrw-hero__next" aria-label="<?php esc_attr_e( 'Next slide', 'soundcreations-rwanda' ); ?>"><svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 18l6-6-6-6"/></svg></button>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</section>
		<?php
	}
);

/** Hero behaviour: slideshow timing, swipe, keyboard, pause; deferred video. */
add_action(
	'wp_footer',
	function () {
		if ( ! is_front_page() ) {
			return;
		}
		?>
<script>
(function(){
var h=document.querySelector('.scrw-hero'); if(h===null){return;}
var reduce=window.matchMedia&&window.matchMedia('(prefers-reduced-motion: reduce)').matches;
var pauseBtn=h.querySelector('.scrw-hero__pause');
function setPaused(p,label){ if(pauseBtn===null){return;} pauseBtn.setAttribute('aria-pressed',p?'true':'false'); pauseBtn.classList.toggle('is-paused',p); pauseBtn.setAttribute('aria-label',label); }
if(h.classList.contains('scrw-hero--video')){
	var v=h.querySelector('video'); var saveData=navigator.connection&&navigator.connection.saveData;
	if(v===null||reduce||saveData){ if(pauseBtn){pauseBtn.hidden=true;} return; }
	window.addEventListener('load',function(){ v.src=v.getAttribute('data-src'); v.addEventListener('playing',function(){h.classList.add('is-playing');},{once:true}); var pr=v.play(); if(pr&&pr.catch){pr.catch(function(){});} });
	if(pauseBtn){ pauseBtn.addEventListener('click',function(){ if(v.paused){v.play();setPaused(false,'Pause video');}else{v.pause();setPaused(true,'Play video');} }); }
	return;
}
var imgs=h.querySelectorAll('.scrw-hero__img'), slides=h.querySelectorAll('.scrw-hero__slide'), dots=h.querySelectorAll('.scrw-hero__dot');
var n=slides.length, cur=0, timer=null, userPaused=reduce, hover=false, ms=parseInt(h.getAttribute('data-interval'),10)||6000;
if(n<2){return;}
function go(i){
	i=(i+n)%n; if(i===cur){return;}
	[imgs,slides,dots].forEach(function(list){ if(list[cur]){list[cur].classList.remove('is-active');} if(list[i]){list[i].classList.add('is-active');} });
	slides[cur].setAttribute('aria-hidden','true'); slides[i].removeAttribute('aria-hidden');
	dots[cur].setAttribute('aria-selected','false'); dots[i].setAttribute('aria-selected','true');
	cur=i; restart();
}
function running(){ return userPaused===false && hover===false && document.hidden===false; }
function restart(){
	clearTimeout(timer); h.classList.remove('is-running'); void h.offsetWidth;
	if(running()){ h.classList.add('is-running'); timer=setTimeout(function(){go(cur+1);},ms); }
}
h.querySelector('.scrw-hero__next').addEventListener('click',function(){go(cur+1);});
h.querySelector('.scrw-hero__prev').addEventListener('click',function(){go(cur-1);});
Array.prototype.forEach.call(dots,function(d,i){d.addEventListener('click',function(){go(i);});});
if(pauseBtn){ pauseBtn.addEventListener('click',function(){ userPaused=userPaused===false; setPaused(userPaused,userPaused?'Play slideshow':'Pause slideshow'); restart(); }); }
if(reduce){ setPaused(true,'Play slideshow'); }
h.addEventListener('mouseenter',function(){hover=true;restart();});
h.addEventListener('mouseleave',function(){hover=false;restart();});
h.addEventListener('focusin',function(){hover=true;restart();});
h.addEventListener('focusout',function(e){ if(h.contains(e.relatedTarget)===false){hover=false;restart();} });
document.addEventListener('visibilitychange',restart);
h.addEventListener('keydown',function(e){ if(e.key==='ArrowRight'){go(cur+1);} else if(e.key==='ArrowLeft'){go(cur-1);} });
var x0=null,y0=null;
h.addEventListener('touchstart',function(e){x0=e.touches[0].clientX;y0=e.touches[0].clientY;},{passive:true});
h.addEventListener('touchend',function(e){ if(x0===null){return;} var dx=e.changedTouches[0].clientX-x0, dy=e.changedTouches[0].clientY-y0; if(Math.abs(dx)>45&&Math.abs(dx)>Math.abs(dy)){go(dx<0?cur+1:cur-1);} x0=null; },{passive:true});
restart();
})();
</script>
		<?php
	},
	30
);
