<?php
/**
 * Single brand.
 *
 * @package SoundCreations
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
while ( have_posts() ) :
	the_post();
	$sc_origin  = sc_field( 'origin' );
	$sc_cat     = sc_field( 'category' );
	$sc_tagline = sc_field( 'tagline' );
	$sc_website = sc_field( 'website' );
	$sc_title   = get_the_title();
	?>
	<div class="sc-container sc-page">
		<?php echo sc_breadcrumb( array( array( 'Home', home_url( '/' ) ), array( 'Brands', get_post_type_archive_link( 'sc_brand' ) ), array( $sc_title, '' ) ) ); ?>
		<div class="sc-brand-hero">
			<div class="sc-brand-hero__logo">
				<?php
				if ( has_post_thumbnail() ) {
					the_post_thumbnail( 'medium' );
				} else {
					echo '<span class="sc-brand-hero__ph">' . esc_html( strtoupper( substr( wp_strip_all_tags( $sc_title ), 0, 2 ) ) ) . '</span>';
				}
				?>
			</div>
			<div>
				<p class="sc-eyebrow"><?php echo esc_html( $sc_cat ? $sc_cat : __( 'Brand', 'soundcreations' ) ); ?></p>
				<h1 style="margin-bottom:.5rem;"><?php echo esc_html( $sc_title ); ?></h1>
				<?php if ( $sc_tagline ) : ?><p class="sc-lead" style="max-width:52ch;"><?php echo esc_html( $sc_tagline ); ?></p><?php endif; ?>
				<?php if ( $sc_origin ) : ?>
					<div class="sc-metalist"><div><span class="k"><?php esc_html_e( 'Origin', 'soundcreations' ); ?></span><span class="v"><?php echo esc_html( $sc_origin ); ?></span></div></div>
				<?php endif; ?>
				<?php if ( $sc_website ) : ?><p style="margin-top:1.25rem;"><a class="sc-btn sc-btn--ghost" href="<?php echo esc_url( $sc_website ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Visit brand website', 'soundcreations' ); ?></a></p><?php endif; ?>
			</div>
		</div>
		<div class="sc-prose" style="margin-bottom:3rem;"><?php the_content(); ?></div>

		<?php
		$sc_bq = new WP_Query(
			array(
				'post_type'      => 'sc_product',
				'posts_per_page' => 12,
				'no_found_rows'  => true,
				'meta_query'     => array( array( 'key' => '_sc_brand_name', 'value' => $sc_title, 'compare' => '=' ) ),
			)
		);
		if ( $sc_bq->have_posts() ) {
			echo '<section><div class="sc-section-head"><h2>' . esc_html( sprintf( __( 'Products from %s', 'soundcreations' ), $sc_title ) ) . '</h2></div><div class="sc-grid">';
			while ( $sc_bq->have_posts() ) {
				$sc_bq->the_post();
				echo sc_media_card( get_the_ID(), sc_field( 'brand_name' ), sc_field( 'model' ) );
			}
			echo '</div></section>';
		}
		wp_reset_postdata();
		?>

		<section style="margin-top:3.5rem;">
			<div class="sc-cta-band">
				<h2><?php echo esc_html( sprintf( __( 'Specifying %s for a project?', 'soundcreations' ), $sc_title ) ); ?></h2>
				<p class="sc-lead" style="margin:0 auto 1.5rem;"><?php esc_html_e( 'Talk to our technical team about availability, pricing and system design.', 'soundcreations' ); ?></p>
				<a class="sc-btn sc-btn--primary" href="<?php echo esc_url( home_url( '/request-a-consultation/' ) ); ?>"><?php esc_html_e( 'Request a Consultation', 'soundcreations' ); ?></a>
			</div>
		</section>
	</div>
	<?php
endwhile;
get_footer();
