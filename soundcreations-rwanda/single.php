<?php
/**
 * Single post template (Rwanda): adds an author and updated-date line so
 * buyer guides show who wrote them and when (2.3.6, E-E-A-T).
 *
 * @package SoundCreationsRwanda
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>
<div class="sc-container sc-page">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<article>
			<header>
				<h1><?php the_title(); ?></h1>
				<p class="scrw-byline"><?php
				printf(
					/* translators: 1: author, 2: date. */
					esc_html__( 'By %1$s · Updated %2$s', 'soundcreations-rwanda' ),
					esc_html__( 'Sound Creations Rwanda technical team', 'soundcreations-rwanda' ),
					'<time datetime="' . esc_attr( get_the_modified_date( 'c' ) ) . '">' . esc_html( get_the_modified_date( 'j F Y' ) ) . '</time>'
				);
				?></p>
			</header>
			<?php if ( has_post_thumbnail() ) : ?>
				<div style="margin:1.5rem 0;border-radius:var(--sc-radius-lg);overflow:hidden;"><?php the_post_thumbnail( 'large', array( 'fetchpriority' => 'high', 'loading' => 'eager' ) ); ?></div>
			<?php endif; ?>
			<div class="sc-page__body"><?php the_content(); ?></div>
		</article>
		<?php
	endwhile;
	?>
</div>
<?php
get_footer();
