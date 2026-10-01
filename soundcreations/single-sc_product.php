<?php
/**
 * Single product.
 *
 * @package SoundCreations
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
while ( have_posts() ) :
	the_post();
	$sc_brand     = sc_field( 'brand_name' );
	$sc_model     = sc_field( 'model' );
	$sc_avail     = sc_field( 'availability' );
	$sc_datasheet = sc_field( 'datasheet' );
	$sc_specs     = sc_render_specs( sc_field( 'specs' ) );
	$sc_cats      = get_the_terms( get_the_ID(), 'sc_product_category' );
	?>
	<div class="sc-container sc-page">
		<?php echo sc_breadcrumb( array( array( 'Home', home_url( '/' ) ), array( 'Products', get_post_type_archive_link( 'sc_product' ) ), array( get_the_title(), '' ) ) ); ?>
		<div class="sc-detail">
			<div class="sc-detail__main">
				<p class="sc-eyebrow"><?php echo esc_html( $sc_brand ? $sc_brand : __( 'Product', 'soundcreations' ) ); ?></p>
				<h1><?php the_title(); ?></h1>
				<div class="sc-detail__media" style="margin:1.5rem 0;">
					<?php
					if ( has_post_thumbnail() ) {
						the_post_thumbnail( 'large' );
					} else {
						echo '<div class="sc-detail__ph">' . esc_html( strtoupper( substr( wp_strip_all_tags( get_the_title() ), 0, 2 ) ) ) . '</div>';
					}
					?>
				</div>
				<div class="sc-prose"><?php the_content(); ?></div>
				<?php
				if ( $sc_specs ) {
					echo '<h2>' . esc_html__( 'Specifications', 'soundcreations' ) . '</h2>';
					echo $sc_specs; // Escaped in builder.
					echo '<p class="sc-lead" style="font-size:.8rem;">' . esc_html__( 'Specifications are indicative. Confirm the current datasheet before ordering.', 'soundcreations' ) . '</p>';
				}
				?>
			</div>
			<aside class="sc-aside">
				<?php if ( $sc_brand ) : ?><div class="sc-aside__row"><span class="sc-aside__label"><?php esc_html_e( 'Brand', 'soundcreations' ); ?></span><span class="sc-aside__val"><?php echo esc_html( $sc_brand ); ?></span></div><?php endif; ?>
				<?php if ( $sc_model ) : ?><div class="sc-aside__row"><span class="sc-aside__label"><?php esc_html_e( 'Model', 'soundcreations' ); ?></span><span class="sc-aside__val"><?php echo esc_html( $sc_model ); ?></span></div><?php endif; ?>
				<?php if ( $sc_avail ) : ?><div class="sc-aside__row"><span class="sc-aside__label"><?php esc_html_e( 'Availability', 'soundcreations' ); ?></span><span class="sc-aside__val"><?php echo esc_html( $sc_avail ); ?></span></div><?php endif; ?>
				<?php
				$sc_apps = sc_term_tags( get_the_ID(), 'sc_application' );
				if ( $sc_apps ) :
					?><div class="sc-aside__row"><span class="sc-aside__label"><?php esc_html_e( 'Applications', 'soundcreations' ); ?></span><?php echo $sc_apps; ?></div><?php endif; ?>
				<a class="sc-btn sc-btn--primary" href="#sc-form"><?php esc_html_e( 'Enquire about this product', 'soundcreations' ); ?></a>
				<a class="sc-btn sc-btn--ghost" href="<?php echo esc_url( home_url( '/request-a-quote/' ) ); ?>"><?php esc_html_e( 'Request a quote', 'soundcreations' ); ?></a>
				<?php if ( $sc_datasheet ) : ?><a class="sc-btn sc-btn--ghost" href="<?php echo esc_url( $sc_datasheet ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Download datasheet', 'soundcreations' ); ?></a><?php endif; ?>
			</aside>
		</div>

		<?php
		$sc_cat_ids = array();
		if ( $sc_cats && ! is_wp_error( $sc_cats ) ) {
			foreach ( $sc_cats as $sc_c ) {
				$sc_cat_ids[] = (int) $sc_c->term_id;
			}
		}
		if ( $sc_cat_ids ) {
			$sc_rel = new WP_Query(
				array(
					'post_type'      => 'sc_product',
					'posts_per_page' => 3,
					'post__not_in'   => array( get_the_ID() ),
					'no_found_rows'  => true,
					'tax_query'      => array( array( 'taxonomy' => 'sc_product_category', 'field' => 'term_id', 'terms' => $sc_cat_ids ) ),
				)
			);
			if ( $sc_rel->have_posts() ) {
				echo '<section style="margin-top:4rem;"><div class="sc-section-head"><h2>' . esc_html__( 'Related products', 'soundcreations' ) . '</h2></div><div class="sc-grid">';
				while ( $sc_rel->have_posts() ) {
					$sc_rel->the_post();
					echo sc_media_card( get_the_ID(), sc_field( 'brand_name' ), sc_field( 'model' ) );
				}
				echo '</div></section>';
			}
			wp_reset_postdata();
		}
		?>

		<?php if ( shortcode_exists( 'sc_enquiry_form' ) ) : ?>
			<section style="margin-top:4rem;">
				<div class="sc-section-head"><p class="sc-eyebrow"><?php esc_html_e( 'Enquiry', 'soundcreations' ); ?></p><h2><?php esc_html_e( 'Ask about this product', 'soundcreations' ); ?></h2></div>
				<?php echo do_shortcode( '[sc_enquiry_form type="product"]' ); ?>
			</section>
		<?php endif; ?>
	</div>
	<?php
endwhile;
get_footer();
