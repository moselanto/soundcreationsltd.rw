<?php
/**
 * Generic taxonomy term archive for catalog content.
 *
 * @package SoundCreations
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
$sc_obj = get_queried_object();
$sc_tax_label = ( $sc_obj && isset( $sc_obj->taxonomy ) && get_taxonomy( $sc_obj->taxonomy ) ) ? get_taxonomy( $sc_obj->taxonomy )->labels->singular_name : __( 'Category', 'soundcreations' );
?>
<div class="sc-container sc-page">
	<header class="sc-archive-head">
		<p class="sc-eyebrow"><?php echo esc_html( $sc_tax_label ); ?></p>
		<h1><?php single_term_title(); ?></h1>
		<?php
		$sc_desc = term_description();
		if ( $sc_desc ) {
			echo '<div class="sc-lead">' . wp_kses_post( $sc_desc ) . '</div>';
		}
		?>
	</header>
	<?php if ( have_posts() ) : ?>
		<div class="sc-grid">
			<?php
			while ( have_posts() ) :
				the_post();
				$sc_ptype = get_post_type();
				if ( 'sc_product' === $sc_ptype ) {
					echo sc_media_card( get_the_ID(), sc_field( 'brand_name' ), sc_field( 'model' ) );
				} elseif ( 'sc_project' === $sc_ptype ) {
					echo sc_media_card( get_the_ID(), sc_field( 'client' ), sc_field( 'year' ) );
				} elseif ( 'sc_brand' === $sc_ptype ) {
					echo sc_media_card( get_the_ID(), sc_field( 'category' ), sc_field( 'tagline' ), true );
				} else {
					echo sc_media_card( get_the_ID() );
				}
			endwhile;
			?>
		</div>
		<div style="margin-top:2.5rem;"><?php the_posts_pagination(); ?></div>
	<?php else : ?>
		<div class="sc-empty"><?php esc_html_e( 'Nothing found in this category yet.', 'soundcreations' ); ?></div>
	<?php endif; ?>
</div>
<?php
get_footer();
