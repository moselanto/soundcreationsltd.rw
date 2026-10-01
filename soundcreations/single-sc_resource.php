<?php
/**
 * Single video / resource page.
 *
 * WHY THIS TEMPLATE WAS CREATED
 * -----------------------------
 * There was no single-sc_resource.php at all, so every /videos/<slug>/ page
 * fell through WordPress's template hierarchy to the generic single.php --
 * a 30-line template that renders a title, a featured image and the body.
 *
 * Three consequences, all found by live audit on 30 September 2026:
 *
 *  1. NO PLAYER. The video was only ever playable from the /videos/ archive,
 *     which builds an iframe on click. The singular page -- the URL that gets
 *     indexed, shared and linked -- contained no video at all. A crawl found
 *     zero YouTube references in the HTML of every one of the five pages.
 *
 *  2. THIN CONTENT. Those pages measured 123-131 words each, the thinnest on
 *     the site, because the video was the content and the video was missing.
 *
 *  3. DISHONEST MARKUP. seo-graph.php emits a VideoObject for these pages,
 *     telling Google there is a watchable video here. Google checks. Schema
 *     describing a video that the page does not contain is exactly the kind
 *     of mismatch that earns a structured-data penalty rather than a rich
 *     result. Fixing the page is what makes the markup truthful.
 *
 * PRIVACY AND PERFORMANCE
 * -----------------------
 * The embed uses youtube-nocookie.com, matching the existing usage in
 * single-sc_solution.php: it defers YouTube's tracking cookies until the
 * visitor actually presses play, which matters for a site publishing a
 * privacy policy. loading="lazy" keeps the iframe off the critical path so
 * the embed does not undo the hero-video weight reduction.
 *
 * @package SoundCreations
 */

if ( defined( 'ABSPATH' ) === false ) {
	exit;
}

get_header();

while ( have_posts() ) :
	the_post();

	$sc_id      = get_the_ID();
	$sc_vid     = sc_youtube_id( (string) sc_field( 'video_url' ) );
	$sc_file    = (string) sc_field( 'file' );
	$sc_body    = get_the_content();
	$sc_hasbody = strlen( trim( wp_strip_all_tags( $sc_body ) ) ) > 0;
	$sc_summary = trim( wp_strip_all_tags( (string) get_the_excerpt() ) );
	$sc_archive = get_post_type_archive_link( 'sc_resource' );
	if ( empty( $sc_archive ) ) {
		$sc_archive = home_url( '/videos/' );
	}
	?>

	<article class="sc-case sc-video-single">
		<div class="sc-container">
			<?php
			echo sc_breadcrumb(
				array(
					array( 'Home', home_url( '/' ) ),
					array( 'Videos', $sc_archive ),
					array( get_the_title(), '' ),
				)
			);
			?>
			<header class="sc-case__head">
				<p class="sc-eyebrow"><?php esc_html_e( 'Video', 'soundcreations' ); ?></p>
				<h1 class="sc-case__title"><?php the_title(); ?></h1>
				<?php if ( '' !== $sc_summary ) : ?>
					<p class="sc-lead sc-case__lead"><?php echo esc_html( $sc_summary ); ?></p>
				<?php endif; ?>
			</header>
		</div>

		<?php if ( '' !== $sc_vid ) : ?>
			<div class="sc-container">
				<div class="sc-embed">
					<iframe
						src="https://www.youtube-nocookie.com/embed/<?php echo esc_attr( $sc_vid ); ?>?rel=0"
						title="<?php echo esc_attr( get_the_title() ); ?>"
						loading="lazy"
						referrerpolicy="strict-origin-when-cross-origin"
						allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
						allowfullscreen></iframe>
				</div>
			</div>
		<?php elseif ( has_post_thumbnail() ) : ?>
			<?php // No video on this resource: fall back to the featured image. ?>
			<div class="sc-case__hero">
				<div class="sc-container">
					<?php the_post_thumbnail( 'large', array( 'class' => 'sc-case__heroimg' ) ); ?>
				</div>
			</div>
		<?php endif; ?>

		<div class="sc-container sc-case__body">
			<div class="sc-case__main">
				<?php if ( $sc_hasbody ) : ?>
					<div class="sc-prose sc-case__prose"><?php the_content(); ?></div>
				<?php endif; ?>

				<?php if ( '' !== $sc_file ) : ?>
					<p class="sc-video-single__download">
						<a class="sc-btn sc-btn--ghost" href="<?php echo esc_url( $sc_file ); ?>" target="_blank" rel="noopener noreferrer">
							<?php esc_html_e( 'Download the resource', 'soundcreations' ); ?>
						</a>
					</p>
				<?php endif; ?>
			</div>
		</div>

		<?php
		/*
		 * Related videos.
		 *
		 * Two purposes beyond navigation: it gives these pages genuine
		 * additional content, and it creates internal links between them. Five
		 * orphaned pages that link to nothing accumulate no authority; a small
		 * connected cluster does.
		 */
		$sc_related = get_posts(
			array(
				'post_type'        => 'sc_resource',
				'post_status'      => 'publish',
				'posts_per_page'   => 3,
				'post__not_in'     => array( $sc_id ),
				'orderby'          => 'rand',
				'no_found_rows'    => true,
				'suppress_filters' => false,
			)
		);
		if ( count( $sc_related ) > 0 ) :
			?>
			<section class="sc-section sc-fane-alt">
				<div class="sc-container">
					<div class="sc-res-head">
						<div>
							<p class="sc-eyebrow"><?php esc_html_e( 'More from our library', 'soundcreations' ); ?></p>
							<h2 style="margin:.15rem 0 0;"><?php esc_html_e( 'Related videos', 'soundcreations' ); ?></h2>
						</div>
					</div>
					<div class="sc-brand-grid">
						<?php
						foreach ( $sc_related as $sc_rel ) :
							$sc_rvid  = sc_youtube_id( (string) get_post_meta( $sc_rel->ID, '_sc_video_url', true ) );
							$sc_rthumb = has_post_thumbnail( $sc_rel->ID )
								? get_the_post_thumbnail_url( $sc_rel->ID, 'medium_large' )
								: ( '' !== $sc_rvid ? 'https://i.ytimg.com/vi/' . $sc_rvid . '/hqdefault.jpg' : '' );
							?>
							<div class="sc-brand-card">
								<?php if ( '' !== $sc_rthumb ) : ?>
									<div class="sc-brand-card__plate">
										<img class="sc-brand-card__img" src="<?php echo esc_url( $sc_rthumb ); ?>" alt="<?php echo esc_attr( get_the_title( $sc_rel->ID ) ); ?>" loading="lazy" decoding="async" width="480" height="360" />
									</div>
								<?php endif; ?>
								<div class="sc-brand-card__body">
									<h3 class="sc-brand-card__name"><?php echo esc_html( get_the_title( $sc_rel->ID ) ); ?></h3>
									<a class="sc-brand-card__link" href="<?php echo esc_url( get_permalink( $sc_rel->ID ) ); ?>">
										<?php esc_html_e( 'Watch', 'soundcreations' ); ?> <span aria-hidden="true">&rarr;</span>
									</a>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
					<p style="margin:1.5rem 0 0;">
						<a class="sc-btn sc-btn--ghost" href="<?php echo esc_url( $sc_archive ); ?>">
							<?php esc_html_e( 'View all videos', 'soundcreations' ); ?>
						</a>
					</p>
				</div>
			</section>
			<?php
		endif;
		?>
	</article>

	<?php
endwhile;

get_footer();
