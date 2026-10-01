<?php
/**
 * Fallback index template.
 *
 * @package SoundCreations
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
get_header();
?>
<div class="sc-container sc-page">
	<?php if ( have_posts() ) : ?>
		<div class="sc-grid">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article class="sc-card">
					<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<p><?php echo esc_html( wp_trim_words( get_the_excerpt(), 24 ) ); ?></p>
				</article>
				<?php
			endwhile;
			?>
		</div>
		<div style="margin-top:2rem;"><?php the_posts_pagination(); ?></div>
	<?php else : ?>
		<p><?php esc_html_e( 'Nothing found.', 'soundcreations' ); ?></p>
	<?php endif; ?>
</div>
<?php
get_footer();
