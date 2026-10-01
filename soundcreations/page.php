<?php
/**
 * Page template - professional, fully editor-driven.
 *
 * Everything on a standard Page is controlled from the WordPress editor:
 *   - Title       -> the page title
 *   - Intro line  -> the page Excerpt (optional)
 *   - Hero image  -> the page Featured image (optional)
 *   - Body        -> the editor content, rendered with .sc-prose styling
 * The SEO title and meta description are set in the "SEO" box below the editor.
 *
 * Slug-specific templates (page-about.php, page-contact.php, page-fane.php,
 * page-request-a-consultation.php) override this file for those pages.
 *
 * @package SoundCreations
 */

if ( defined( 'ABSPATH' ) === false ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	$sc_excerpt = has_excerpt() ? get_the_excerpt() : '';
	$sc_parent  = wp_get_post_parent_id( get_the_ID() );
	$sc_trail   = array( array( __( 'Home', 'soundcreations' ), home_url( '/' ) ) );
	if ( $sc_parent > 0 ) {
		$sc_trail[] = array( get_the_title( $sc_parent ), get_permalink( $sc_parent ) );
	}
	$sc_trail[] = array( get_the_title(), '' );
	?>
	<article class="sc-page">
		<div class="sc-container">
			<?php echo sc_breadcrumb( $sc_trail ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- first-party trusted markup. ?>
			<header class="sc-pagehero">
				<h1 class="sc-pagehero__title"><?php the_title(); ?></h1>
				<?php if ( strlen( $sc_excerpt ) > 0 ) : ?>
					<p class="sc-lead sc-pagehero__lead"><?php echo esc_html( $sc_excerpt ); ?></p>
				<?php endif; ?>
			</header>
		</div>

		<?php if ( has_post_thumbnail() ) : ?>
			<div class="sc-page__hero-media">
				<div class="sc-container"><?php the_post_thumbnail( 'large', array( 'class' => 'sc-page__hero-img', 'loading' => 'eager' ) ); ?></div>
			</div>
		<?php endif; ?>

		<div class="sc-container">
			<div class="sc-prose sc-page__body">
				<?php the_content(); ?>
			</div>
			<?php
			wp_link_pages(
				array(
					'before' => '<div class="sc-page__nav">' . esc_html__( 'Pages:', 'soundcreations' ),
					'after'  => '</div>',
				)
			);
			?>
		</div>
	</article>
	<?php
endwhile;

get_footer();
