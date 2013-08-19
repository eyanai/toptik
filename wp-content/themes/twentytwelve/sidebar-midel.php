<?php
/**
 * The sidebar containing the front page widget areas.
 *
 * If no active widgets in either sidebar, they will be hidden completely.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

/*
 * The front page widget area is triggered if any of the areas
 * have widgets. So let's check that first.
 *
 * If none of the sidebars have widgets, then let's bail early.
 */
if ( ! is_active_sidebar( 'sidebar-5' ) && ! is_active_sidebar( 'sidebar-6' ) )
	return;

// If we get this far, we have widgets. Let do this.
?>

<div id="banners" class="widget-area middel" role="complementary">
	<?php if ( is_active_sidebar( 'sidebar-5' ) ) : ?>
	<div class="first banner">
		<?php dynamic_sidebar( 'sidebar-5' ); ?>
	</div><!-- .first -->
	<?php endif; ?>

	<?php if ( is_active_sidebar( 'sidebar-6' ) ) : ?>
	<div class="second banner">
		<?php dynamic_sidebar( 'sidebar-6' ); ?>
	</div><!-- .second -->
	<?php endif; ?>
</div><!-- #secondary -->