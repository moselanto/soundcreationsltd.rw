<?php
/**
 * Single post template.
 *
 * @package SoundCreations
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
			<header><h1><?php the_title(); ?></h1></header>
			<?php if ( has_post_thumbnail() ) : ?>
				<div style="margin:1.5rem 0;border-radius:var(--sc-radius-lg);overflow:hidden;"><?php the_post_thumbnail( 'large' ); ?></div>
			<?php endif; ?>
			<div class="sc-page__body"><?php the_content(); ?></div>
		</article>
		<?php
	endwhile;
	?>
</div>
<?php
get_footer();
