<?php
/**
 * Rwanda product catalogue (/products/ and product category archives).
 * All products on one page with instant category, brand and text filters.
 *
 * @package SoundCreationsRwanda
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();

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
	$specs = array_slice( array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', (string) get_post_meta( $pid, '_sc_specs', true ) ) ) ), 0, 2 );
	$scrw_items[] = array(
		'id'    => $pid,
		'title' => get_the_title(),
		'url'   => get_permalink(),
		'img'   => has_post_thumbnail() ? get_the_post_thumbnail_url( $pid, 'medium_large' ) : '',
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
<section class="scrw-cat-hero">
	<div class="sc-container">
		<p class="sc-eyebrow"><?php esc_html_e( 'Product Catalogue', 'soundcreations-rwanda' ); ?></p>
		<h1><?php esc_html_e( 'Professional audio, AV and instruments in Kigali.', 'soundcreations-rwanda' ); ?></h1>
		<p class="sc-lead"><?php esc_html_e( 'Genuine equipment from Yamaha, Shure, dB Technologies and more, in stock or available to order from our Kigali showroom, with manufacturer warranty and local support.', 'soundcreations-rwanda' ); ?></p>
		<div class="scrw-cat-hero__actions">
			<a class="sc-btn sc-btn--primary" href="<?php echo esc_url( home_url( '/request-a-quote/' ) ); ?>"><?php esc_html_e( 'Request a quote', 'soundcreations-rwanda' ); ?></a>
			<a class="sc-btn sc-btn--ghost" href="<?php echo esc_url( home_url( '/brands/' ) ); ?>"><?php esc_html_e( 'Our brands', 'soundcreations-rwanda' ); ?></a>
		</div>
	</div>
</section>

<section class="sc-section scrw-cat" data-scrw-cat>
	<div class="sc-container">
		<div class="scrw-cat__bar">
			<div class="scrw-cat__chips" role="group" aria-label="<?php esc_attr_e( 'Filter by category', 'soundcreations-rwanda' ); ?>">
				<button type="button" class="scrw-chip<?php echo '' === $scrw_active ? ' is-active' : ''; ?>" data-cat=""><?php esc_html_e( 'All', 'soundcreations-rwanda' ); ?> <span><?php echo count( $scrw_items ); ?></span></button>
				<?php foreach ( $scrw_cats as $slug => $name ) : ?>
					<button type="button" class="scrw-chip<?php echo $slug === $scrw_active ? ' is-active' : ''; ?>" data-cat="<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $name ); ?></button>
				<?php endforeach; ?>
			</div>
			<div class="scrw-cat__tools">
				<select data-scrw-brand aria-label="<?php esc_attr_e( 'Filter by brand', 'soundcreations-rwanda' ); ?>">
					<option value=""><?php esc_html_e( 'All brands', 'soundcreations-rwanda' ); ?></option>
					<?php foreach ( $scrw_brands as $slug => $name ) : ?>
						<option value="<?php echo esc_attr( $slug ); ?>"><?php echo esc_html( $name ); ?></option>
					<?php endforeach; ?>
				</select>
				<input type="search" data-scrw-search placeholder="<?php esc_attr_e( 'Search products or models', 'soundcreations-rwanda' ); ?>" aria-label="<?php esc_attr_e( 'Search products', 'soundcreations-rwanda' ); ?>">
			</div>
		</div>

		<div class="scrw-pgrid">
			<?php foreach ( $scrw_items as $it ) : ?>
				<article class="scrw-pcard" data-cat="<?php echo esc_attr( $it['cat'] ); ?>" data-brand="<?php echo esc_attr( sanitize_title( $it['brand'] ) ); ?>" data-text="<?php echo esc_attr( strtolower( $it['title'] . ' ' . $it['model'] . ' ' . $it['brand'] . ' ' . $it['catn'] ) ); ?>">
					<a class="scrw-pcard__plate" href="<?php echo esc_url( $it['url'] ); ?>" tabindex="-1" aria-hidden="true">
						<?php if ( $it['img'] ) : ?>
							<img src="<?php echo esc_url( $it['img'] ); ?>" alt="" loading="lazy" decoding="async" width="400" height="400">
						<?php else : ?>
							<span class="scrw-pcard__ph"><?php echo esc_html( $it['brand'] ? $it['brand'] : $it['title'] ); ?></span>
						<?php endif; ?>
					</a>
					<div class="scrw-pcard__body">
						<p class="scrw-pcard__meta"><?php echo esc_html( trim( $it['brand'] . ( $it['catn'] ? ' · ' . $it['catn'] : '' ) ) ); ?></p>
						<h2 class="scrw-pcard__title"><a href="<?php echo esc_url( $it['url'] ); ?>"><?php echo esc_html( $it['title'] ); ?></a></h2>
						<?php if ( $it['specs'] ) : ?>
							<ul class="scrw-pcard__specs">
								<?php foreach ( $it['specs'] as $s ) : ?><li><?php echo esc_html( $s ); ?></li><?php endforeach; ?>
							</ul>
						<?php endif; ?>
						<div class="scrw-pcard__actions">
							<a class="scrw-pcard__link" href="<?php echo esc_url( $it['url'] ); ?>"><?php esc_html_e( 'View details', 'soundcreations-rwanda' ); ?> &rarr;</a>
							<a class="scrw-pcard__quote" href="<?php echo esc_url( add_query_arg( 'product', rawurlencode( $it['title'] ), home_url( '/request-a-quote/' ) ) ); ?>"><?php esc_html_e( 'Get a quote', 'soundcreations-rwanda' ); ?></a>
						</div>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
		<p class="scrw-cat__empty" data-scrw-empty hidden><?php esc_html_e( 'No products match. Try another category or contact us, we can source it for you.', 'soundcreations-rwanda' ); ?></p>
		<p class="scrw-cat__note"><?php esc_html_e( 'Specifications are indicative; confirm the current datasheet before ordering. Prices on request.', 'soundcreations-rwanda' ); ?></p>
	</div>
</section>

<script>
(function(){
	var root=document.querySelector('[data-scrw-cat]'); if(!root){return;}
	var chips=root.querySelectorAll('.scrw-chip'), cards=root.querySelectorAll('.scrw-pcard');
	var brand=root.querySelector('[data-scrw-brand]'), search=root.querySelector('[data-scrw-search]'), empty=root.querySelector('[data-scrw-empty]');
	var cat=(root.querySelector('.scrw-chip.is-active')||{}).getAttribute ? root.querySelector('.scrw-chip.is-active').getAttribute('data-cat') : '';
	function apply(){
		var b=brand.value, t=search.value.trim().toLowerCase(), shown=0;
		for(var i=0;i<cards.length;i++){
			var c=cards[i];
			var ok=(cat===''||c.getAttribute('data-cat')===cat)&&(b===''||c.getAttribute('data-brand')===b)&&(t===''||c.getAttribute('data-text').indexOf(t)>-1);
			c.hidden=(ok===false); if(ok){shown++;}
		}
		empty.hidden=shown>0;
	}
	for(var i=0;i<chips.length;i++){chips[i].addEventListener('click',function(){
		for(var j=0;j<chips.length;j++){chips[j].classList.remove('is-active');}
		this.classList.add('is-active'); cat=this.getAttribute('data-cat'); apply();
	});}
	brand.addEventListener('change',apply); search.addEventListener('input',apply); apply();
})();
</script>
<?php
get_footer();
