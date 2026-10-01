<?php
/**
 * Single project - professional case study.
 *
 * Structured fields (edited in the "Project Details" box in wp-admin):
 * client (Client / venue), location, year, summary, scope (one per line),
 * brands_used, and gallery (photos). The full write-up is the post body.
 * Everything on this page is editable - nothing is hardcoded.
 *
 * @package SoundCreations
 */

if ( defined( 'ABSPATH' ) === false ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	$sc_client  = sc_field( 'client' );
	$sc_loc     = sc_field( 'location' );
	$sc_year    = sc_field( 'year' );
	$sc_summary = sc_field( 'summary' );
	$sc_scope   = sc_render_ticklist( sc_field( 'scope' ) );
	$sc_brands  = sc_field( 'brands_used' );
	$sc_btags   = sc_term_tags( get_the_ID(), 'sc_brand_tax' );
	$sc_eyebrow = sc_primary_term_name( get_the_ID(), 'sc_industry', __( 'Project', 'soundcreations' ) );
	$sc_gallery = array_filter( array_map( 'absint', explode( ',', (string) sc_field( 'gallery' ) ) ) );
	$sc_body    = get_the_content();
	$sc_hasbody = strlen( trim( wp_strip_all_tags( $sc_body ) ) ) > 0;
	$sc_challenge  = (string) sc_field( 'challenge' );
	$sc_solution   = (string) sc_field( 'solution' );
	$sc_technology = sc_field( 'technology' );
	$sc_result     = (string) sc_field( 'result' );
	?>
	<article class="sc-case">
		<div class="sc-container">
			<?php echo sc_breadcrumb( array( array( 'Home', home_url( '/' ) ), array( 'Projects', get_post_type_archive_link( 'sc_project' ) ), array( get_the_title(), '' ) ) ); ?>
			<header class="sc-case__head">
				<p class="sc-eyebrow"><?php echo esc_html( $sc_eyebrow ); ?></p>
				<h1 class="sc-case__title"><?php the_title(); ?></h1>
				<?php if ( $sc_summary ) : ?>
					<p class="sc-lead sc-case__lead"><?php echo esc_html( $sc_summary ); ?></p>
				<?php endif; ?>
			</header>
		</div>

		<?php if ( has_post_thumbnail() ) : ?>
			<div class="sc-case__hero">
				<div class="sc-container"><?php the_post_thumbnail( 'large', array( 'class' => 'sc-case__heroimg', 'loading' => 'eager' ) ); ?></div>
			</div>
		<?php endif; ?>

		<div class="sc-container sc-case__body">
			<div class="sc-case__main">
				<?php if ( $sc_hasbody ) : ?>
					<div class="sc-prose sc-case__prose"><?php the_content(); ?></div>
				<?php endif; ?>

				<?php if ( strlen( trim( $sc_challenge ) ) > 0 || strlen( trim( $sc_solution ) ) > 0 || strlen( trim( (string) $sc_technology ) ) > 0 || strlen( trim( $sc_result ) ) > 0 ) : ?>
					<div class="sc-case__blocks">
						<?php if ( strlen( trim( $sc_challenge ) ) > 0 ) : ?>
							<section class="sc-case__block">
								<span class="sc-case__block-label"><?php esc_html_e( 'The Challenge', 'soundcreations' ); ?></span>
								<div class="sc-prose sc-case__prose"><?php echo wpautop( esc_html( $sc_challenge ) ); ?></div>
							</section>
						<?php endif; ?>
						<?php if ( strlen( trim( $sc_solution ) ) > 0 ) : ?>
							<section class="sc-case__block">
								<span class="sc-case__block-label"><?php esc_html_e( 'The Solution', 'soundcreations' ); ?></span>
								<div class="sc-prose sc-case__prose"><?php echo wpautop( esc_html( $sc_solution ) ); ?></div>
							</section>
						<?php endif; ?>
						<?php $sc_tech_html = sc_render_ticklist( $sc_technology ); ?>
						<?php if ( strlen( $sc_tech_html ) > 0 ) : ?>
							<section class="sc-case__block sc-case__block--tech">
								<span class="sc-case__block-label"><?php esc_html_e( 'The Technology', 'soundcreations' ); ?></span>
								<?php echo $sc_tech_html; ?>
							</section>
						<?php endif; ?>
						<?php if ( strlen( trim( $sc_result ) ) > 0 ) : ?>
							<section class="sc-case__block">
								<span class="sc-case__block-label"><?php esc_html_e( 'The Result', 'soundcreations' ); ?></span>
								<div class="sc-prose sc-case__prose"><?php echo wpautop( esc_html( $sc_result ) ); ?></div>
							</section>
						<?php endif; ?>
					</div>
				<?php endif; ?>

				<?php if ( count( $sc_gallery ) > 0 ) : ?>
					<section class="sc-case__gallery" aria-label="<?php esc_attr_e( 'Project photos', 'soundcreations' ); ?>">
						<h2 class="sc-case__h2"><?php esc_html_e( 'Project gallery', 'soundcreations' ); ?></h2>
						<div class="sc-gallery-grid" data-sc-gallery>
							<?php
							foreach ( $sc_gallery as $gid ) :
								$full = wp_get_attachment_image_url( $gid, 'full' );
								$img  = wp_get_attachment_image( $gid, 'large', false, array( 'class' => 'sc-gallery-grid__img', 'loading' => 'lazy' ) );
								if ( $img ) :
									?>
									<a class="sc-gallery-grid__item" href="<?php echo esc_url( $full ); ?>" target="_blank" rel="noopener"><?php echo $img; ?><span class="sc-gallery-grid__zoom" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="7"></circle><line x1="20" y1="20" x2="16" y2="16"></line><line x1="11" y1="8.5" x2="11" y2="13.5"></line><line x1="8.5" y1="11" x2="13.5" y2="11"></line></svg></span></a>
									<?php
								endif;
							endforeach;
							?>
						</div>
							<div class="sc-lightbox" data-sc-lightbox hidden aria-hidden="true" role="dialog" aria-modal="true" aria-label="Project photo viewer">
								<button class="sc-lightbox__close" type="button" data-lb-close aria-label="Close viewer">&times;</button>
								<button class="sc-lightbox__nav sc-lightbox__nav--prev" type="button" data-lb-prev aria-label="Previous photo">&lsaquo;</button>
								<figure class="sc-lightbox__stage">
									<img class="sc-lightbox__img" data-lb-img src="" alt="" decoding="async">
									<figcaption class="sc-lightbox__caption" data-lb-caption></figcaption>
								</figure>
								<button class="sc-lightbox__nav sc-lightbox__nav--next" type="button" data-lb-next aria-label="Next photo">&rsaquo;</button>
								<span class="sc-lightbox__counter" data-lb-counter></span>
							</div>
							<script>
							(function(){
								var gallery = document.querySelector('[data-sc-gallery]');
								var box = document.querySelector('[data-sc-lightbox]');
								if (gallery === null || box === null) { return; }
								document.body.appendChild(box);
								var items = Array.prototype.slice.call(gallery.querySelectorAll('.sc-gallery-grid__item'));
								if (items.length === 0) { return; }
								var imgEl = box.querySelector('[data-lb-img]');
								var capEl = box.querySelector('[data-lb-caption]');
								var cntEl = box.querySelector('[data-lb-counter]');
								var prevBtn = box.querySelector('[data-lb-prev]');
								var nextBtn = box.querySelector('[data-lb-next]');
								var closeBtn = box.querySelector('[data-lb-close]');
								var current = 0;
								var lastFocus = null;
								function capFor(i){
									var im = items[i].querySelector('img');
									if (im === null) { return ''; }
									return im.getAttribute('alt') || '';
								}
								function render(){
									imgEl.setAttribute('src', items[current].getAttribute('href'));
									var c = capFor(current);
									imgEl.setAttribute('alt', c);
									capEl.textContent = c;
									cntEl.textContent = (current + 1) + ' / ' + items.length;
									var solo = items.length < 2;
									prevBtn.style.display = solo ? 'none' : '';
									nextBtn.style.display = solo ? 'none' : '';
								}
								function openAt(i){
									current = i;
									lastFocus = document.activeElement;
									render();
									box.hidden = false;
									box.setAttribute('aria-hidden', 'false');
									document.documentElement.style.overflow = 'hidden';
									closeBtn.focus();
								}
								function closeBox(){
									box.hidden = true;
									box.setAttribute('aria-hidden', 'true');
									document.documentElement.style.overflow = '';
									if (lastFocus) { lastFocus.focus(); }
								}
								function go(delta){
									current = (current + delta + items.length) % items.length;
									render();
								}
								items.forEach(function(item, i){
									item.addEventListener('click', function(ev){ ev.preventDefault(); openAt(i); });
								});
								prevBtn.addEventListener('click', function(){ go(-1); });
								nextBtn.addEventListener('click', function(){ go(1); });
								closeBtn.addEventListener('click', closeBox);
								box.addEventListener('click', function(ev){ if (ev.target === box || ev.target === imgEl.parentNode) { closeBox(); } });
								document.addEventListener('keydown', function(ev){
									if (box.hidden === true) { return; }
									if (ev.key === 'Escape') { closeBox(); }
									else if (ev.key === 'ArrowLeft') { go(-1); }
									else if (ev.key === 'ArrowRight') { go(1); }
								});
								var startX = 0;
								box.addEventListener('touchstart', function(ev){ startX = ev.changedTouches[0].clientX; }, { passive: true });
								box.addEventListener('touchend', function(ev){
									var dx = ev.changedTouches[0].clientX - startX;
									if (Math.abs(dx) > 40) { go(dx < 0 ? 1 : -1); }
								}, { passive: true });
							})();
							</script>
					</section>
				<?php endif; ?>
			</div>

			<aside class="sc-case__aside">
				<div class="sc-factcard">
					<h2 class="sc-factcard__title"><?php esc_html_e( 'Project details', 'soundcreations' ); ?></h2>
					<dl class="sc-factcard__list">
						<?php if ( $sc_client ) : ?>
							<div class="sc-factcard__row"><dt><?php esc_html_e( 'Client / venue', 'soundcreations' ); ?></dt><dd><?php echo esc_html( $sc_client ); ?></dd></div>
						<?php endif; ?>
						<?php if ( $sc_loc ) : ?>
							<div class="sc-factcard__row"><dt><?php esc_html_e( 'Location', 'soundcreations' ); ?></dt><dd><?php echo esc_html( $sc_loc ); ?></dd></div>
						<?php endif; ?>
						<?php if ( $sc_year ) : ?>
							<div class="sc-factcard__row"><dt><?php esc_html_e( 'Year', 'soundcreations' ); ?></dt><dd><?php echo esc_html( $sc_year ); ?></dd></div>
						<?php endif; ?>
					</dl>

					<?php if ( $sc_scope ) : ?>
						<div class="sc-factcard__block">
							<span class="sc-factcard__label"><?php esc_html_e( 'Scope of work', 'soundcreations' ); ?></span>
							<?php echo $sc_scope; ?>
						</div>
					<?php endif; ?>

					<?php if ( $sc_btags ) : ?>
						<div class="sc-factcard__block">
							<span class="sc-factcard__label"><?php esc_html_e( 'Brands used', 'soundcreations' ); ?></span>
							<?php echo $sc_btags; ?>
						</div>
					<?php elseif ( $sc_brands ) : ?>
						<div class="sc-factcard__block">
							<span class="sc-factcard__label"><?php esc_html_e( 'Brands used', 'soundcreations' ); ?></span>
							<div class="sc-tags">
								<?php
								$sc_bparts = array_filter( array_map( 'trim', explode( ',', $sc_brands ) ) );
								foreach ( $sc_bparts as $sc_b ) {
									echo '<span class="sc-tag">' . esc_html( $sc_b ) . '</span>';
								}
								?>
							</div>
						</div>
					<?php endif; ?>

					<a class="sc-btn sc-btn--primary sc-factcard__cta" href="<?php echo esc_url( home_url( '/request-a-consultation/' ) ); ?>"><?php esc_html_e( 'Start a similar project', 'soundcreations' ); ?></a>
				</div>
			</aside>
		</div>

		<div class="sc-container">
			<div class="sc-cta-band sc-cta-band--compact sc-case__cta">
				<h2><?php esc_html_e( 'Planning a similar project?', 'soundcreations' ); ?></h2>
				<p class="sc-lead"><?php esc_html_e( 'Tell us about your space and requirements and our team will design a system that fits.', 'soundcreations' ); ?></p>
				<a class="sc-btn sc-btn--primary" href="<?php echo esc_url( home_url( '/request-a-consultation/' ) ); ?>"><?php esc_html_e( 'Start a similar project', 'soundcreations' ); ?></a>
			</div>
		</div>
	</article>
	<?php
endwhile;

get_footer();
