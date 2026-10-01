<?php
/**
 * Resource archive - owns the /resources/ URL (sc_resource CPT).
 *
 * Video-first: any resource with a YouTube URL renders as a click-to-play
 * video card; remaining resources (a download file, or just a permalink)
 * render below as download cards. Everything is data-driven from published
 * sc_resource posts (ordered by menu_order, then newest) and editable in
 * wp-admin. To add a video: Resources > Add New > paste a YouTube link in
 * the "YouTube video URL" field > Publish.
 *
 * @package SoundCreations
 */

if ( defined( 'ABSPATH' ) === false ) {
	exit;
}
get_header();

$sc_hero_img = SC_THEME_URI . '/assets/img/support-hero.jpg';
$sc_cta_img  = SC_THEME_URI . '/assets/img/support-cta.jpg';
$sc_consult  = home_url( '/request-a-consultation/' );
$sc_arrow    = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>';
$sc_dl       = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>';
$sc_playicon = '<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg>';

$sc_q = new WP_Query(
	array(
		'post_type'      => 'sc_resource',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => array(
			'menu_order' => 'ASC',
			'date'       => 'DESC',
		),
		'no_found_rows'  => true,
	)
);
$sc_videos    = array();
$sc_downloads = array();
if ( $sc_q->have_posts() ) {
	while ( $sc_q->have_posts() ) {
		$sc_q->the_post();
		$rid  = get_the_ID();
		$vurl = (string) get_post_meta( $rid, '_sc_video_url', true );
		$vid  = function_exists( 'sc_youtube_id' ) ? sc_youtube_id( $vurl ) : '';
		$file = (string) get_post_meta( $rid, '_sc_file', true );
		$desc = has_excerpt( $rid ) ? get_the_excerpt() : wp_trim_words( wp_strip_all_tags( get_the_content() ), 22 );
		if ( strlen( $vid ) > 0 ) {
			$sc_videos[] = array( 'id' => $vid, 'title' => get_the_title(), 'desc' => $desc );
		} else {
			$sc_downloads[] = array(
				'title' => get_the_title(),
				'desc'  => $desc,
				'href'  => ( strlen( $file ) > 0 ) ? $file : get_permalink( $rid ),
				'ext'   => ( strlen( $file ) > 0 ),
			);
		}
	}
	wp_reset_postdata();
}
?>

<section class="sc-support-hero" style="background-image:url('<?php echo esc_url( $sc_hero_img ); ?>');">
	<span class="sc-support-hero__scrim" aria-hidden="true"></span>
	<div class="sc-container sc-support-hero__inner">
		<nav class="sc-crumb" aria-label="Breadcrumb"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a> <span aria-hidden="true">&rsaquo;</span> <span class="sc-crumb__cur"><?php esc_html_e( 'Videos', 'soundcreations' ); ?></span></nav>
		<p class="sc-eyebrow"><?php echo esc_html( sc_setting( 'resources_eyebrow', 'Videos' ) ); ?></p>
		<h1 class="sc-support-hero__title"><?php echo esc_html( sc_setting( 'resources_title', 'Videos & resources.' ) ); ?></h1>
		<p class="sc-lead sc-support-hero__lead"><?php echo sc_rich_e( sc_setting( 'resources_lead', 'Watch demos, installations and product highlights, plus manuals and datasheets for the systems and brands we supply and support.' ) ); ?></p>
	</div>
</section>

<?php if ( count( $sc_videos ) > 0 ) : ?>
<section class="sc-section">
	<div class="sc-container">
		<div class="sc-res-head">
			<div>
				<p class="sc-eyebrow"><?php esc_html_e( 'Watch', 'soundcreations' ); ?></p>
				<h2 style="margin:.15rem 0 0;"><?php echo esc_html( sc_setting( 'resources_videos_title', 'Videos' ) ); ?></h2>
			</div>
		</div>
		<div class="sc-video-grid">
			<?php foreach ( $sc_videos as $v ) : ?>
				<article class="sc-video-card">
					<button type="button" class="sc-video-card__frame" data-sc-yt="<?php echo esc_attr( $v['id'] ); ?>" aria-label="<?php echo esc_attr( 'Play: ' . $v['title'] ); ?>">
						<img class="sc-video-card__thumb" src="https://img.youtube.com/vi/<?php echo esc_attr( $v['id'] ); ?>/hqdefault.jpg" loading="lazy" alt="<?php echo esc_attr( $v['title'] ); ?>" width="480" height="360">
						<span class="sc-video-card__play" aria-hidden="true"><?php echo $sc_playicon; ?></span>
					</button>
					<div class="sc-video-card__body">
						<h3 class="sc-video-card__title"><?php echo esc_html( $v['title'] ); ?></h3>
						<?php if ( strlen( (string) $v['desc'] ) > 0 ) : ?><p class="sc-video-card__desc"><?php echo esc_html( $v['desc'] ); ?></p><?php endif; ?>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<?php if ( count( $sc_downloads ) > 0 ) : ?>
<section class="sc-section">
	<div class="sc-container">
		<div class="sc-res-head">
			<div>
				<p class="sc-eyebrow"><?php esc_html_e( 'Downloads & documentation', 'soundcreations' ); ?></p>
				<h2 style="margin:.15rem 0 0;"><?php echo esc_html( sc_setting( 'resources_grid_title', 'Manuals, datasheets & guides.' ) ); ?></h2>
			</div>
		</div>
		<div class="sc-res-grid">
			<?php foreach ( $sc_downloads as $d ) : ?>
				<a class="sc-res-card" href="<?php echo esc_url( $d['href'] ); ?>"<?php echo $d['ext'] ? ' target="_blank" rel="noopener"' : ''; ?>>
					<span class="sc-res-card__icon" aria-hidden="true"><?php echo $sc_dl; ?></span>
					<span class="sc-res-card__text"><strong><?php echo esc_html( $d['title'] ); ?></strong><span><?php echo esc_html( $d['desc'] ); ?></span></span>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<?php if ( count( $sc_videos ) === 0 && count( $sc_downloads ) === 0 ) : ?>
<section class="sc-section">
	<div class="sc-container">
		<p class="sc-empty"><?php esc_html_e( 'No videos yet. In wp-admin, go to Videos > Add New, paste a YouTube link in the YouTube video URL field, and publish.', 'soundcreations' ); ?></p>
	</div>
</section>
<?php endif; ?>

<section class="sc-section">
	<div class="sc-container">
		<div class="sc-cta-band sc-cta-band--photo" style="background-image:url('<?php echo esc_url( $sc_cta_img ); ?>');">
			<div class="sc-cta-band__inner">
				<h2><?php echo esc_html( sc_setting( 'resources_cta_title', 'Looking for something specific?' ) ); ?></h2>
				<p class="sc-lead" style="margin:0 0 1.5rem;"><?php echo sc_rich_e( sc_setting( 'resources_cta_text', 'Our technical team can point you to the right video, manual or datasheet for your system.' ) ); ?></p>
				<a class="sc-btn sc-btn--primary" href="<?php echo esc_url( $sc_consult ); ?>"><?php esc_html_e( 'Contact Our Team', 'soundcreations' ); ?> <?php echo $sc_arrow; ?></a>
			</div>
		</div>
	</div>
</section>

<script>
( function () {
	function buildFrame( id, title ) {
		var f = document.createElement( 'iframe' );
		f.className = 'sc-video-card__iframe';
		f.src = 'https://www.youtube.com/embed/' + id + '?autoplay=1&rel=0';
		f.title = title || 'Video';
		f.setAttribute( 'frameborder', '0' );
		f.setAttribute( 'allow', 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share' );
		f.setAttribute( 'allowfullscreen', 'true' );
		return f;
	}
	document.addEventListener( 'click', function ( e ) {
		var btn = e.target.closest ? e.target.closest( '[data-sc-yt]' ) : null;
		if ( btn === null ) {
			return;
		}
		e.preventDefault();
		var id = btn.getAttribute( 'data-sc-yt' );
		if ( id && id.length > 0 ) {
			btn.parentNode.replaceChild( buildFrame( id, btn.getAttribute( 'aria-label' ) ), btn );
		}
	} );
} )();
</script>

<?php
get_footer();
