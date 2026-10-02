<?php
/**
 * Project archive. Owns the /projects/ URL (sc_project has_archive => projects).
 * Cards are data-driven from published sc_project posts, ordered by menu_order
 * (editable in wp-admin). Category / location / solution filtering and search are
 * client-side (see [data-sc-projfilter] wiring in assets/js/theme.js).
 *
 * @package SoundCreations
 */

if ( defined( 'ABSPATH' ) === false ) {
	exit;
}
get_header();

$sc_archive  = get_post_type_archive_link( 'sc_project' );
if ( empty( $sc_archive ) ) {
	$sc_archive = home_url( '/projects/' );
}
$sc_consult  = home_url( '/request-a-consultation/' );
$sc_hero_img = apply_filters( 'sc_projects_hero_img', SC_THEME_URI . '/assets/img/projects-hero.jpg' );
$sc_hero_alt = apply_filters( 'sc_projects_hero_alt', __( 'Concert hall and auditorium installation', 'soundcreations' ) );
$sc_cta_img  = SC_THEME_URI . '/assets/img/projects-cta.webp';
$sc_arrow    = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>';
$sc_pinicon  = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>';

// Category pills (fixed set matching the design). Slug must match sanitize_title of each card's category.
$sc_cat_pills = array( 'Acoustic', 'Audio', 'Visual' );

// Collect projects (data-driven, ordered by menu_order).
$sc_q = new WP_Query(
	array(
		'post_type'      => 'sc_project',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
		'orderby'        => array( 'menu_order' => 'ASC', 'title' => 'ASC' ),
		'no_found_rows'  => true,
	)
);
$sc_items = array();
$sc_sols  = array();
$sc_locs  = array();
if ( $sc_q->have_posts() ) {
	while ( $sc_q->have_posts() ) {
		$sc_q->the_post();
		$pid   = get_the_ID();
		$cat   = (string) get_post_meta( $pid, '_sc_category', true );
		$badge = (string) get_post_meta( $pid, '_sc_badge', true );
		$loc   = (string) get_post_meta( $pid, '_sc_location', true );
		$sol   = (string) get_post_meta( $pid, '_sc_solution', true );
		$sum   = (string) get_post_meta( $pid, '_sc_summary', true );
		$img = sc_project_card_image( $pid );
		if ( '' !== $sol && ! in_array( $sol, $sc_sols, true ) ) {
			$sc_sols[] = $sol;
		}
		if ( '' !== $loc && ! in_array( $loc, $sc_locs, true ) ) {
			$sc_locs[] = $loc;
		}
		$division_meta = (string) get_post_meta( $pid, '_sc_division', true );
		if ( strlen( $division_meta ) > 0 ) {
			$divs = array_values( array_filter( array_map( 'trim', explode( ',', $division_meta ) ) ) );
		} else {
			$hay  = strtolower( $sol . ' ' . $cat . ' ' . $sum . ' ' . get_the_title() );
			$divs = array();
			if ( is_int( strpos( $hay, 'acoustic' ) ) || is_int( strpos( $hay, 'reverb' ) ) || is_int( strpos( $hay, 'stone-wool' ) ) || is_int( strpos( $hay, 'ceiling' ) ) || is_int( strpos( $hay, 'treatment' ) ) ) {
				$divs[] = 'Acoustic';
			}
			if ( is_int( strpos( $hay, 'audio' ) ) || is_int( strpos( $hay, 'sound' ) ) || is_int( strpos( $hay, 'pa system' ) ) || is_int( strpos( $hay, 'loudspeaker' ) ) || is_int( strpos( $hay, 'amplif' ) ) || is_int( strpos( $hay, 'monitor' ) ) || is_int( strpos( $hay, 'front-of-house' ) ) || is_int( strpos( $hay, 'subwoofer' ) ) || is_int( strpos( $hay, 'console' ) ) || is_int( strpos( $hay, 'reinforcement' ) ) || is_int( strpos( $hay, ' av ' ) ) ) {
				$divs[] = 'Audio';
			}
			if ( is_int( strpos( $hay, 'visual' ) ) || is_int( strpos( $hay, 'video' ) ) || is_int( strpos( $hay, 'lecture' ) ) || is_int( strpos( $hay, 'projection' ) ) || is_int( strpos( $hay, 'display' ) ) || is_int( strpos( $hay, 'screen' ) ) || is_int( strpos( $hay, ' av ' ) ) || is_int( strpos( $hay, 'integrat' ) ) ) {
				$divs[] = 'Visual';
			}
			if ( count( $divs ) === 0 ) {
				$divs[] = 'Audio';
			}
		}
		$division_slugs = array();
		foreach ( $divs as $d ) {
			$division_slugs[] = sanitize_title( $d );
		}
		$sc_items[] = array(
			'title' => get_the_title(),
			'href'  => get_permalink( $pid ),
			'cat'   => implode( ' ', $division_slugs ),
			'badge' => implode( ' / ', $divs ),
			'loc'   => $loc,
			'sol'   => $sol,
			'sum'   => $sum,
			'img'   => $img,
		);
	}
	wp_reset_postdata();
}
?>

<section class="sc-projhero">
	<div class="sc-container sc-projhero__grid">
		<div class="sc-projhero__text">
			<?php echo sc_breadcrumb( array( array( 'Home', home_url( '/' ) ), array( 'Projects', '' ) ) ); ?>
			<p class="sc-eyebrow"><?php echo esc_html( sc_setting( 'projects_eyebrow', 'Our Projects' ) ); ?></p>
			<h1 class="sc-projhero__title"><?php echo esc_html( sc_setting( 'projects_title', 'Real solutions. Real impact.' ) ); ?></h1>
			<p class="sc-lead sc-projhero__lead"><?php echo sc_rich_e( sc_setting( 'projects_lead', 'Explore a selection of our professional audio, acoustics and integration projects across Africa and the Middle East.' ) ); ?></p>
			<a class="sc-btn sc-btn--primary sc-projhero__btn" href="<?php echo esc_url( $sc_consult ); ?>"><?php esc_html_e( 'Start Your Project', 'soundcreations' ); ?> <?php echo $sc_arrow; ?></a>
		</div>
		<div class="sc-projhero__media">
			<img src="<?php echo esc_url( $sc_hero_img ); ?>" alt="<?php echo esc_attr( $sc_hero_alt ); ?>" loading="eager" decoding="async">
		</div>
	</div>
</section>

<section class="sc-section sc-projfilter-sec">
	<div class="sc-container" data-sc-projfilter>
		<div class="sc-projfilters">
			<div class="sc-projfilters__row">
				<span class="sc-projfilters__label"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg> <?php esc_html_e( 'Filter by Category', 'soundcreations' ); ?></span>
				<div class="sc-projfilters__pills">
					<button type="button" class="sc-projpill is-active" data-proj-cat="all"><?php esc_html_e( 'All Projects', 'soundcreations' ); ?></button>
					<?php foreach ( $sc_cat_pills as $c ) : ?>
						<button type="button" class="sc-projpill" data-proj-cat="<?php echo esc_attr( sanitize_title( $c ) ); ?>"><?php echo esc_html( $c ); ?></button>
					<?php endforeach; ?>
				</div>
			</div>
			<div class="sc-projfilters__row sc-projfilters__row--controls">
				<label class="sc-projctrl sc-projctrl--search">
					<span class="sc-projfilters__label"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg> <?php esc_html_e( 'Search Projects', 'soundcreations' ); ?></span>
					<input type="search" data-proj-search placeholder="<?php esc_attr_e( 'Search project, venue or solution...', 'soundcreations' ); ?>">
				</label>
			</div>
		</div>

		<div class="sc-projfeat-head">
			<h2><?php esc_html_e( 'Featured Projects', 'soundcreations' ); ?></h2>
			<a class="sc-linkbtn" href="<?php echo esc_url( $sc_archive ); ?>"><?php esc_html_e( 'View All Projects', 'soundcreations' ); ?> <span aria-hidden="true">&rarr;</span></a>
		</div>

		<?php if ( count( $sc_items ) > 0 ) : ?>
			<div class="sc-projgrid">
				<?php
				foreach ( $sc_items as $it ) :
					$text = strtolower( $it['title'] . ' ' . $it['loc'] . ' ' . $it['cat'] . ' ' . $it['sol'] . ' ' . $it['sum'] );
					?>
					<article class="sc-projcard" data-card data-category="<?php echo esc_attr( $it['cat'] ); ?>" data-location="<?php echo esc_attr( sanitize_title( $it['loc'] ) ); ?>" data-solution="<?php echo esc_attr( sanitize_title( $it['sol'] ) ); ?>" data-text="<?php echo esc_attr( $text ); ?>">
						<a class="sc-projcard__media" href="<?php echo esc_url( $it['href'] ); ?>" style="background-image:url('<?php echo esc_url( $it['img'] ); ?>');">
							<?php if ( '' !== $it['badge'] ) : ?>
								<span class="sc-projcard__badge"><?php echo esc_html( $it['badge'] ); ?></span>
							<?php endif; ?>
							<?php if ( '' !== $it['loc'] ) : ?>
								<span class="sc-projcard__loc"><?php echo $sc_pinicon; ?> <?php echo esc_html( $it['loc'] ); ?></span>
							<?php endif; ?>
						</a>
						<div class="sc-projcard__body">
							<h3 class="sc-projcard__title"><?php echo esc_html( $it['title'] ); ?></h3>
							<?php if ( '' !== $it['sum'] ) : ?>
								<p class="sc-projcard__desc"><?php echo esc_html( $it['sum'] ); ?></p>
							<?php endif; ?>
							<a class="sc-projcard__link" href="<?php echo esc_url( $it['href'] ); ?>"><?php esc_html_e( 'View Project', 'soundcreations' ); ?> <span aria-hidden="true">&rarr;</span></a>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
			<p class="sc-projempty" data-proj-empty hidden><?php esc_html_e( 'No projects match your filters.', 'soundcreations' ); ?></p>
		<?php else : ?>
			<div class="sc-empty"><?php esc_html_e( 'No projects published yet. In wp-admin, open Sound Creations -> Sample Catalog to add starter items.', 'soundcreations' ); ?></div>
		<?php endif; ?>
	</div>
</section>

<?php
// Projects proof stats. These numbers were hardcoded here AND on the homepage,
// which is how the site ended up advertising two different project counts.
// They now render from the "Projects stats" field in Sound Creations ->
// Settings (proj_stats), one stat per line as: Number | Label | Sub-note.
$sc_pstat_icons = array(
	0 => '<span class="sc-stat__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="9" r="6"/><path d="m12 6.4 1.13 2.29 2.53.37-1.83 1.78.43 2.52L12 12.06l-2.26 1.19.43-2.52-1.83-1.78 2.53-.37z"/><path d="M9 14.4 7.5 21l4.5-2.6L16.5 21 15 14.4"/></svg></span>',
	1 => '<span class="sc-stat__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M3 12h18"/><path d="M12 3c2.6 2.5 4 5.6 4 9s-1.4 6.5-4 9c-2.6-2.5-4-5.6-4-9s1.4-6.5 4-9z"/></svg></span>',
	2 => '<span class="sc-stat__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M9 4.5h6a1 1 0 0 1 1 1V6a1 1 0 0 1-1 1H9a1 1 0 0 1-1-1v-.5a1 1 0 0 1 1-1z"/><path d="M8 5.5H6a2 2 0 0 0-2 2V19a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5a2 2 0 0 0-2-2h-2"/><path d="m8.5 13.5 2.2 2.2 4.3-4.3"/></svg></span>',
	3 => '<span class="sc-stat__icon" aria-hidden="true"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M16 19v-1.5a3.5 3.5 0 0 0-3.5-3.5h-5A3.5 3.5 0 0 0 4 17.5V19"/><circle cx="10" cy="8" r="3.2"/><path d="M20 19v-1.5a3.5 3.5 0 0 0-2.6-3.4"/><path d="M15.4 5a3.2 3.2 0 0 1 0 6"/></svg></span>',
);
$sc_pstat_raw = (string) sc_setting( 'proj_stats', '' );

/*
 * SINGLE SOURCE OF TRUTH FOR THE PROOF STATS  (30 Sep 2026)
 *
 * A previous pass moved these numbers out of the template and into the
 * proj_stats setting -- see the comment above -- but left a hardcoded
 * DEFAULT for proj_stats in inc/template-tags.php that still carried the
 * old figures. sc_setting() falls back to that default whenever the field
 * is empty, so the contradiction survived the refactor and the site went on
 * publishing two different numbers for the same facts:
 *
 *     homepage        22+ years      4 locations      850+ projects
 *     projects page   20+ years      4 offices        300+ projects
 *
 * A visitor comparing the two pages sees a company that cannot count its
 * own projects, and 850 versus 300 is not a rounding difference. Publishing
 * mutually contradictory claims is also precisely the pattern that gets a
 * business flagged for misrepresentation.
 *
 * Fix: the homepage stat fields are now the ONE place these facts live. When
 * proj_stats is empty this page derives its tiles from them, so editing the
 * numbers in Settings updates both pages together and they cannot drift
 * apart again. proj_stats is kept as a deliberate override for the rare case
 * where this page should show something different -- but it now starts empty,
 * so matching is the default behaviour rather than something to remember.
 */
if ( '' === trim( $sc_pstat_raw ) ) {
	$sc_derived = array();
	for ( $sc_si = 1; $sc_si <= 4; $sc_si++ ) {
		$sc_dnum  = trim( (string) sc_setting( 'home_stat' . $sc_si . '_num', '' ) );
		$sc_dlab  = trim( (string) sc_setting( 'home_stat' . $sc_si . '_label', '' ) );
		$sc_dnote = trim( (string) sc_setting( 'home_stat' . $sc_si . '_note', '' ) );
		if ( '' === $sc_dnum && '' === $sc_dlab ) {
			continue;
		}
		$sc_derived[] = $sc_dnum . ' | ' . $sc_dlab . ' | ' . $sc_dnote;
	}
	$sc_pstat_raw = implode( "\n", $sc_derived );
}
$sc_pstats    = array();
foreach ( preg_split( '/\r\n|\r|\n/', $sc_pstat_raw ) as $sc_line ) {
	$sc_line = trim( $sc_line );
	if ( '' === $sc_line ) {
		continue;
	}
	$sc_bits = array_map( 'trim', explode( '|', $sc_line ) );
	$sc_pstats[] = array(
		'num'   => isset( $sc_bits[0] ) ? $sc_bits[0] : '',
		'label' => isset( $sc_bits[1] ) ? $sc_bits[1] : '',
		'note'  => isset( $sc_bits[2] ) ? $sc_bits[2] : '',
	);
}
if ( count( $sc_pstats ) > 0 ) :
	?>
<section class="sc-stats sc-stats--proof">
	<div class="sc-container">
		<div class="sc-stats__grid">
			<?php foreach ( $sc_pstats as $sc_pi => $sc_ps ) : ?>
			<div class="sc-stat">
				<?php echo $sc_pstat_icons[ $sc_pi % count( $sc_pstat_icons ) ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted inline SVG ?>
				<div class="sc-stat__body">
					<div class="sc-stat__head sc-stat__head--num"><?php echo esc_html( $sc_ps['num'] ); ?></div>
					<div class="sc-stat__sub"><?php echo esc_html( $sc_ps['label'] ); ?><?php if ( '' !== $sc_ps['note'] ) : ?><span class="sc-stat__note"><?php echo esc_html( $sc_ps['note'] ); ?></span><?php endif; ?></div>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<section class="sc-section">
	<div class="sc-container">
		<div class="sc-cta-band sc-cta-band--photo" style="background-image:url('<?php echo esc_url( $sc_cta_img ); ?>');">
			<div class="sc-cta-band__inner">
				<h2><?php echo esc_html( sc_setting( 'projects_cta_title', 'Have a project in mind?' ) ); ?></h2>
				<p class="sc-lead" style="margin:0 0 1.5rem;"><?php echo sc_rich_e( sc_setting( 'projects_cta_text', 'Our team of experts is ready to help you design and deliver the right solution.' ) ); ?></p>
				<a class="sc-btn sc-btn--primary" href="<?php echo esc_url( $sc_consult ); ?>"><?php esc_html_e( 'Request a Consultation', 'soundcreations' ); ?> <?php echo $sc_arrow; ?></a>
			</div>
		</div>
	</div>
</section>

<?php
get_footer();
