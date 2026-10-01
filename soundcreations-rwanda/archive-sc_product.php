<?php
/**
 * Rwanda /products/ page: the group brands page (What we offer, represented
 * brands, Why professionals trust our products) followed by the Rwanda
 * product catalogue with category, brand and text filters.
 *
 * Based on the parent theme's brand archive. Owns the /brands/ URL (sc_brand CPT has_archive => brands).
 * The brand grid is data-driven from published sc_brand posts, ordered by the
 * post "Order" (menu_order) field so the team can reorder brands in wp-admin
 * without code. Each card shows the brand logo from
 * assets/img/brands/logos/{_sc_logo}.png, falling back to a styled wordmark.
 *
 * @package SoundCreations
 */

if ( defined( 'ABSPATH' ) === false ) {
	exit;
}
get_header();

$sc_img  = SC_THEME_URI . '/assets/img/fane';
$sc_sol  = SC_THEME_URI . '/assets/img/solutions';
$sc_brd  = SC_THEME_URI . '/assets/img/brands';

$sc_hero_img   = $sc_brd . '/brands-hero.jpg';
$sc_brands_url = get_post_type_archive_link( 'sc_brand' );
if ( empty( $sc_brands_url ) ) {
	$sc_brands_url = home_url( '/brands/' );
}
$sc_products_url = get_post_type_archive_link( 'sc_product' );
if ( empty( $sc_products_url ) ) {
	$sc_products_url = home_url( '/products/' );
}

$sc_cats = array(
	array( 'Loudspeaker Components', '<rect x="6" y="3" width="12" height="18" rx="2"/><circle cx="12" cy="14" r="3"/><circle cx="12" cy="7" r="1"/>' ),
	array( 'Loudspeaker Systems', '<rect x="5" y="2" width="6" height="20" rx="1"/><rect x="13" y="2" width="6" height="20" rx="1"/><circle cx="8" cy="8" r="1.5"/><circle cx="16" cy="8" r="1.5"/>' ),
	array( 'Electronics & Amplification', '<line x1="4" y1="21" x2="4" y2="14"/><line x1="4" y1="10" x2="4" y2="3"/><line x1="12" y1="21" x2="12" y2="12"/><line x1="12" y1="8" x2="12" y2="3"/><line x1="20" y1="21" x2="20" y2="16"/><line x1="20" y1="12" x2="20" y2="3"/><line x1="1" y1="14" x2="7" y2="14"/><line x1="9" y1="8" x2="15" y2="8"/><line x1="17" y1="16" x2="23" y2="16"/>' ),
	array( 'Microphones & Wireless', '<path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"/><path d="M19 10v2a7 7 0 0 1-14 0v-2"/><line x1="12" y1="19" x2="12" y2="23"/><line x1="8" y1="23" x2="16" y2="23"/>' ),
	array( 'Digital & Mixing Consoles', '<rect x="3" y="3" width="18" height="18" rx="2"/><line x1="8" y1="7" x2="8" y2="17"/><line x1="12" y1="7" x2="12" y2="17"/><line x1="16" y1="7" x2="16" y2="17"/><circle cx="8" cy="10" r="1.4"/><circle cx="12" cy="14" r="1.4"/><circle cx="16" cy="9" r="1.4"/>' ),
	array( 'Acoustics & Installation', '<path d="M2 10v4"/><path d="M6 6v12"/><path d="M10 3v18"/><path d="M14 8v8"/><path d="M18 5v14"/><path d="M22 10v4"/>' ),
	array( 'Cables & Infrastructure', '<path d="M9 2v6"/><path d="M15 2v6"/><path d="M7 8h10v3a5 5 0 0 1-10 0z"/><path d="M12 16v6"/>' ),
);

$sc_stats = array(
	array( '11+', 'Global Brands', '<path d="M12 2 2 7l10 5 10-5-10-5z"/><path d="m2 17 10 5 10-5"/><path d="m2 12 10 5 10-5"/>' ),
	array( '100%', 'Genuine Products', '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="m9 12 2 2 4-4"/>' ),
	array( 'Expert', 'Technical Support', '<path d="M3 18v-6a9 9 0 0 1 18 0v6"/><path d="M21 19a2 2 0 0 1-2 2h-1a2 2 0 0 1-2-2v-3a2 2 0 0 1 2-2h3z"/><path d="M3 19a2 2 0 0 0 2 2h1a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2H3z"/>' ),
	array( 'Regional', 'Logistics & Service', '<circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>' ),
);

$sc_arrow = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>';
?>

<section class="sc-support-hero sc-brands-hero" style="background-image:url('<?php echo esc_url( $sc_hero_img ); ?>');">
	<span class="sc-support-hero__scrim" aria-hidden="true"></span>
	<div class="sc-container sc-support-hero__inner">
		<nav class="sc-crumb" aria-label="Breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> <span aria-hidden="true">&rsaquo;</span> <span class="sc-crumb__cur"><?php esc_html_e( 'Products', 'soundcreations' ); ?></span></nav>
		<p class="sc-eyebrow"><?php echo esc_html( sc_setting( 'products_eyebrow', 'What we offer' ) ); ?></p>
		<?php
		// This archive had NO h1 at all, while every sibling archive
		// (solutions, projects, videos) has one. A crawl on 29 Sep 2026
		// confirmed /brands/ was the only landing page on the site with
		// zero h1, so the strongest on-page heading signal for every
		// brand-and-distribution query was simply absent. Default copy
		// leads with the category rather than a slogan, matching the
		// title tag generated in seo-titles.php.
		?>
		<h1 class="sc-support-hero__title"><?php echo esc_html( sc_setting( 'products_title', 'Professional audio, acoustic and AV brands.' ) ); ?></h1>
		<p class="sc-lead sc-support-hero__lead"><?php echo esc_html( sc_setting( 'products_lead', 'Professional sound and acoustic solutions.' ) ); ?></p>
	</div>
</section>

<section class="sc-section sc-fane-alt">
	<div class="sc-container">
		<div class="sc-res-head">
			<div>
				<p class="sc-eyebrow"><?php esc_html_e( 'Our represented products brands', 'soundcreations' ); ?></p>
				<h2 style="margin:.15rem 0 0;"><?php echo esc_html( sc_setting( 'brands_grid_title', 'Partnering with the world class brands.' ) ); ?></h2>
			</div>
		</div>
		<?php
		$sc_q = new WP_Query(
			array(
				'post_type'      => 'sc_brand',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'orderby'        => array(
					'menu_order' => 'ASC',
					'title'      => 'ASC',
				),
				'no_found_rows'  => true,
			)
		);
		if ( $sc_q->have_posts() ) :
			?>
			<div class="sc-brand-grid">
				<?php
				while ( $sc_q->have_posts() ) :
					$sc_q->the_post();
					$sc_id    = get_the_ID();
					$sc_name  = get_the_title();
					$sc_cat   = (string) get_post_meta( $sc_id, '_sc_category', true );
					$sc_desc  = (string) get_post_meta( $sc_id, '_sc_tagline', true );
					if ( '' === $sc_desc ) {
						$sc_desc = wp_trim_words( wp_strip_all_tags( get_the_content() ), 22 );
					}
					$sc_logo  = (string) get_post_meta( $sc_id, '_sc_logo', true );
					$sc_rel   = 'assets/img/brands/logos/' . $sc_logo . '.png';
					$sc_logo_url = ( '' !== $sc_logo && file_exists( get_theme_file_path( $sc_rel ) ) ) ? get_theme_file_uri( $sc_rel ) : '';
					// Prefer a logo set in wp-admin (Featured image) so brand-logo edits reflect on the front end.
					if ( has_post_thumbnail( $sc_id ) ) {
						$sc_logo_url = get_the_post_thumbnail_url( $sc_id, 'full' );
					}

					$sc_slug = get_post_field( 'post_name', $sc_id );
					if ( 'fane' === $sc_slug ) {
						$sc_href = home_url( '/fane/' );
					} else {
						$sc_href = get_permalink( $sc_id );
					}
					?>
					<div class="sc-brand-card">
						<div class="sc-brand-card__plate">
							<?php if ( '' !== $sc_logo_url ) : ?>
								<img class="sc-brand-card__img" src="<?php echo esc_url( $sc_logo_url ); ?>" alt="<?php echo esc_attr( $sc_name ); ?> logo" loading="lazy" decoding="async" />
							<?php else : ?>
								<span class="sc-brand-card__mark"><?php echo esc_html( $sc_name ); ?></span>
							<?php endif; ?>
						</div>
						<div class="sc-brand-card__body">
							<h3 class="sc-brand-card__name"><?php echo esc_html( $sc_name ); ?></h3>
							<?php if ( '' !== $sc_cat ) : ?>
								<p class="sc-brand-card__cat"><?php echo esc_html( $sc_cat ); ?></p>
							<?php endif; ?>
							<?php if ( '' !== $sc_desc ) : ?>
								<p class="sc-brand-card__desc"><?php echo esc_html( $sc_desc ); ?></p>
							<?php endif; ?>
							<?php $sc_web = (string) get_post_meta( $sc_id, '_sc_website', true ); ?>
							<?php if ( strlen( $sc_web ) > 0 ) : ?>
								<a class="sc-brand-card__link" href="<?php echo esc_url( $sc_web ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Visit Website', 'soundcreations' ); ?> <span aria-hidden="true">&rarr;</span></a>
							<?php else : ?>
								<a class="sc-brand-card__link" href="<?php echo esc_url( $sc_href ); ?>"><?php esc_html_e( 'View Brand', 'soundcreations' ); ?> <span aria-hidden="true">&rarr;</span></a>
							<?php endif; ?>
						</div>
					</div>
					<?php
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		<?php else : ?>
			<p class="sc-empty"><?php esc_html_e( 'Brands will appear here soon. In wp-admin, open Sound Creations → Sample Catalog and run it to populate the brand list.', 'soundcreations' ); ?></p>
		<?php endif; ?>
	</div>
</section>

<section class="sc-section">
	<div class="sc-container sc-brands-why">
		<div class="sc-brands-why__intro">
			<p class="sc-eyebrow"><?php esc_html_e( 'Why professionals trust our products', 'soundcreations' ); ?></p>
			<h2><?php echo esc_html( sc_setting( 'brands_why_title', 'Global technology. Local expertise.' ) ); ?></h2>
			<p><?php echo esc_html( sc_setting( 'brands_why_body', 'We partner with leading global manufacturers to bring you reliable, innovative and performance-driven solutions for every project.' ) ); ?></p>
		</div>
		<div class="sc-brands-stats">
			<?php foreach ( $sc_stats as $s ) : ?>
				<div class="sc-brands-stat">
					<span class="sc-brands-stat__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><?php echo $s[2]; ?></svg></span>
					<strong><?php echo esc_html( $s[0] ); ?></strong>
					<span><?php echo esc_html( $s[1] ); ?></span>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php
$scrw_q = new WP_Query(
	array(
		'post_type'      => 'sc_product',
		'post_status'    => 'publish',
		'posts_per_page' => 300,
		'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
		'no_found_rows'  => true,
	)
);
$scrw_items  = array();
$scrw_cats   = array();
$scrw_brands = array();
while ( $scrw_q->have_posts() ) {
	$scrw_q->the_post();
	$pid   = get_the_ID();
	$terms = get_the_terms( $pid, 'sc_product_category' );
	$cat   = ( $terms && ! is_wp_error( $terms ) ) ? reset( $terms ) : null;
	$brand = trim( (string) get_post_meta( $pid, '_sc_brand_name', true ) );
	if ( $cat ) {
		$scrw_cats[ $cat->slug ] = $cat->name;
	}
	if ( '' !== $brand ) {
		$scrw_brands[ sanitize_title( $brand ) ] = $brand;
	}
	$specs = array_slice( array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) get_post_meta( $pid, '_sc_specs', true ) ) ) ), 0, 3 );
	$scrw_items[] = array(
		'id'    => $pid,
		'title' => get_the_title(),
		'url'   => get_permalink(),
		'img'   => scrw_product_image_url( $pid, 'large' ),
		'brand' => $brand,
		'model' => (string) get_post_meta( $pid, '_sc_model', true ),
		'cat'   => $cat ? $cat->slug : '',
		'catn'  => $cat ? $cat->name : '',
		'specs' => $specs,
	);
}
wp_reset_postdata();
asort( $scrw_cats );
asort( $scrw_brands );
$scrw_active = is_tax( 'sc_product_category' ) ? get_queried_object()->slug : '';
?>
<section class="sc-section scrw-cat" id="catalogue" data-scrw-cat>
	<div class="sc-container">
		<div class="scrw-cat__head">
			<div>
				<p class="sc-eyebrow"><?php esc_html_e( 'Products available in Rwanda', 'soundcreations-rwanda' ); ?></p>
				<h2><?php esc_html_e( 'Shop our product range.', 'soundcreations-rwanda' ); ?></h2>
				<p class="sc-lead"><?php esc_html_e( 'Genuine equipment with full manufacturer specifications, in stock or available to order from our Kigali showroom, with warranty and local support.', 'soundcreations-rwanda' ); ?></p>
			</div>
			<ul class="scrw-cat__trust">
				<li><span aria-hidden="true">&#10003;</span><?php esc_html_e( 'Genuine products, full warranty', 'soundcreations-rwanda' ); ?></li>
				<li><span aria-hidden="true">&#10003;</span><?php esc_html_e( 'Authorised Yamaha distributor', 'soundcreations-rwanda' ); ?></li>
				<li><span aria-hidden="true">&#10003;</span><?php esc_html_e( 'Installation and support in Rwanda', 'soundcreations-rwanda' ); ?></li>
			</ul>
		</div>

		<div class="scrw-cat__bar">
			<div class="scrw-cat__chips" role="group" aria-label="<?php esc_attr_e( 'Filter by category', 'soundcreations-rwanda' ); ?>">
				<button type="button" class="scrw-chip<?php echo '' === $scrw_active ? ' is-active' : ''; ?>" data-cat=""><?php esc_html_e( 'All products', 'soundcreations-rwanda' ); ?></button>
				<?php foreach ( $scrw_cats as $slug => $name ) : ?>
					<button type="button" class="scrw-chip<?php echo $slug === $scrw_active ? ' is-active' : ''; ?>" data-cat="<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $name ); ?></button>
				<?php endforeach; ?>
			</div>
			<div class="scrw-cat__tools">
				<label class="scrw-field scrw-field--search">
					<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
					<input type="search" data-scrw-search placeholder="<?php esc_attr_e( 'Search model or product', 'soundcreations-rwanda' ); ?>" aria-label="<?php esc_attr_e( 'Search products', 'soundcreations-rwanda' ); ?>">
				</label>
				<select class="scrw-field" data-scrw-brand aria-label="<?php esc_attr_e( 'Filter by brand', 'soundcreations-rwanda' ); ?>">
					<option value=""><?php esc_html_e( 'All brands', 'soundcreations-rwanda' ); ?></option>
					<?php foreach ( $scrw_brands as $slug => $name ) : ?>
						<option value="<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $name ); ?></option>
					<?php endforeach; ?>
				</select>
				<select class="scrw-field" data-scrw-sort aria-label="<?php esc_attr_e( 'Sort products', 'soundcreations-rwanda' ); ?>">
					<option value="default"><?php esc_html_e( 'Featured', 'soundcreations-rwanda' ); ?></option>
					<option value="az"><?php esc_html_e( 'Name A-Z', 'soundcreations-rwanda' ); ?></option>
					<option value="brand"><?php esc_html_e( 'Brand', 'soundcreations-rwanda' ); ?></option>
				</select>
			</div>
		</div>
		<p class="scrw-cat__count" data-scrw-count aria-live="polite"></p>

		<div class="scrw-pgrid" data-scrw-grid>
			<?php foreach ( $scrw_items as $i => $it ) : ?>
				<?php $scrw_is_yamaha = ( 'yamaha' === strtolower( $it['brand'] ) ); ?>
				<article class="scrw-pcard" data-order="<?php echo (int) $i; ?>" data-title="<?php echo esc_attr( strtolower( $it['title'] ) ); ?>" data-cat="<?php echo esc_attr( $it['cat'] ); ?>" data-brand="<?php echo esc_attr( sanitize_title( $it['brand'] ) ); ?>" data-text="<?php echo esc_attr( strtolower( $it['title'] . ' ' . $it['model'] . ' ' . $it['brand'] . ' ' . $it['catn'] ) ); ?>">
					<a class="scrw-pcard__plate" href="<?php echo esc_url( $it['url'] ); ?>" tabindex="-1" aria-hidden="true">
						<?php if ( $scrw_is_yamaha ) : ?><span class="scrw-pcard__badge"><?php esc_html_e( 'Authorised distributor', 'soundcreations-rwanda' ); ?></span><?php endif; ?>
						<?php if ( $it['img'] ) : ?>
							<img src="<?php echo esc_url( $it['img'] ); ?>" alt="<?php echo esc_attr( $it['title'] ); ?>" loading="lazy" decoding="async" width="600" height="600">
						<?php else : ?>
							<span class="scrw-pcard__ph"><?php echo esc_html( $it['brand'] ? $it['brand'] : $it['title'] ); ?></span>
						<?php endif; ?>
					</a>
					<div class="scrw-pcard__body">
						<p class="scrw-pcard__meta"><span class="scrw-pcard__brand"><?php echo esc_html( $it['brand'] ); ?></span><?php if ( $it['catn'] ) : ?><span class="scrw-pcard__cat"><?php echo esc_html( $it['catn'] ); ?></span><?php endif; ?></p>
						<h3 class="scrw-pcard__title"><a href="<?php echo esc_url( $it['url'] ); ?>"><?php echo esc_html( $it['title'] ); ?></a></h3>
						<?php if ( $it['specs'] ) : ?>
							<dl class="scrw-pcard__specs">
								<?php foreach ( $it['specs'] as $s ) : ?>
									<?php
									$scrw_parts = explode( ':', $s, 2 );
									$scrw_lab   = trim( preg_replace( '/^.*?\s-\s/', '', $scrw_parts[0] ) );
									$scrw_val   = isset( $scrw_parts[1] ) ? trim( $scrw_parts[1] ) : '';
									?>
									<div><dt><?php echo esc_html( $scrw_lab ); ?></dt><dd><?php echo esc_html( $scrw_val ); ?></dd></div>
								<?php endforeach; ?>
							</dl>
						<?php endif; ?>
						<div class="scrw-pcard__actions">
							<a class="scrw-btn scrw-btn--ghost" href="<?php echo esc_url( $it['url'] ); ?>"><?php esc_html_e( 'View details', 'soundcreations-rwanda' ); ?></a>
							<a class="scrw-btn scrw-btn--primary" href="<?php echo esc_url( add_query_arg( 'product', rawurlencode( $it['title'] ), home_url( '/request-a-quote/' ) ) ); ?>"><?php esc_html_e( 'Get a quote', 'soundcreations-rwanda' ); ?></a>
						</div>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
		<p class="scrw-cat__empty" data-scrw-empty hidden><?php esc_html_e( 'No products match your filters. Try another category, or ask us: we can source most professional audio equipment for you.', 'soundcreations-rwanda' ); ?></p>

		<div class="scrw-cat__help">
			<div>
				<h3><?php esc_html_e( 'Can’t find what you need?', 'soundcreations-rwanda' ); ?></h3>
				<p><?php esc_html_e( 'This is a selection of our range. Tell us the model or the job and our Kigali team will advise, quote and source it.', 'soundcreations-rwanda' ); ?></p>
			</div>
			<div class="scrw-cat__help-actions">
				<a class="scrw-btn scrw-btn--primary" href="<?php echo esc_url( home_url( '/request-a-quote/' ) ); ?>"><?php esc_html_e( 'Request a quote', 'soundcreations-rwanda' ); ?></a>
				<?php $scrw_wa = function_exists( 'sc_setting' ) ? preg_replace( '/[^0-9]/', '', (string) sc_setting( 'whatsapp' ) ) : ''; ?>
				<?php if ( '' !== $scrw_wa ) : ?><a class="scrw-btn scrw-btn--ghost" href="<?php echo esc_url( 'https://wa.me/' . $scrw_wa ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'WhatsApp us', 'soundcreations-rwanda' ); ?></a><?php endif; ?>
			</div>
		</div>
		<p class="scrw-cat__note"><?php esc_html_e( 'Specifications are from the manufacturers and indicative; confirm the current datasheet before ordering. Prices on request.', 'soundcreations-rwanda' ); ?></p>
	</div>
</section>

<script>
(function(){
	var root=document.querySelector('[data-scrw-cat]'); if(root===null){return;}
	var grid=root.querySelector('[data-scrw-grid]'), chips=root.querySelectorAll('.scrw-chip'), cards=Array.prototype.slice.call(root.querySelectorAll('.scrw-pcard'));
	var brand=root.querySelector('[data-scrw-brand]'), search=root.querySelector('[data-scrw-search]'), sort=root.querySelector('[data-scrw-sort]'), empty=root.querySelector('[data-scrw-empty]'), count=root.querySelector('[data-scrw-count]');
	var act=root.querySelector('.scrw-chip.is-active'); var cat=act ? act.getAttribute('data-cat') : '';
	function apply(){
		var b=brand.value, t=search.value.trim().toLowerCase(), shown=0;
		cards.forEach(function(c){
			var ok=(cat===''||c.getAttribute('data-cat')===cat)&&(b===''||c.getAttribute('data-brand')===b)&&(t===''||c.getAttribute('data-text').indexOf(t)>-1);
			c.hidden=(ok===false); if(ok){shown++;}
		});
		empty.hidden=shown>0;
		count.textContent=shown+(shown===1?' product':' products');
	}
	function order(){
		var m=sort.value, list=cards.slice();
		list.sort(function(a,b){
			if(m==='az'){return a.getAttribute('data-title').localeCompare(b.getAttribute('data-title'));}
			if(m==='brand'){var x=a.getAttribute('data-brand').localeCompare(b.getAttribute('data-brand')); return x===0 ? a.getAttribute('data-title').localeCompare(b.getAttribute('data-title')) : x;}
			return a.getAttribute('data-order')-b.getAttribute('data-order');
		});
		list.forEach(function(c){grid.appendChild(c);});
	}
	chips.forEach ? chips.forEach(bind) : Array.prototype.forEach.call(chips,bind);
	function bind(ch){ch.addEventListener('click',function(){
		Array.prototype.forEach.call(chips,function(x){x.classList.remove('is-active');});
		ch.classList.add('is-active'); cat=ch.getAttribute('data-cat'); apply();
	});}
	brand.addEventListener('change',apply); search.addEventListener('input',apply); sort.addEventListener('change',function(){order();apply();});
	apply();
})();
</script>
<section class="sc-section">
	<div class="sc-container">
		<div class="sc-cta-band sc-cta-band--photo" style="background-image:url('<?php echo esc_url( $sc_brd . '/partner.jpg' ); ?>');">
			<div class="sc-cta-band__inner">
				<h2><?php echo esc_html( sc_setting( 'brands_cta_title', 'Become a partner' ) ); ?></h2>
				<p class="sc-lead" style="margin:0 0 1.5rem;"><?php echo esc_html( sc_setting( 'brands_cta_text', 'Work with Sound Creations to bring your innovative products to the East Africa and Middle East markets.' ) ); ?></p>
				<a class="sc-btn sc-btn--primary sc-brands-partner__btn" href="<?php echo esc_url( home_url( '/request-a-consultation/' ) ); ?>"><?php esc_html_e( 'Partner With Us', 'soundcreations' ); ?> <?php echo $sc_arrow; ?></a>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
