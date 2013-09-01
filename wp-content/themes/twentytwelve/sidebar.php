<?php
/**
 * The sidebar containing the main widget area.
 *
 * If no active widgets in sidebar, let's hide it completely.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>


	<?php if ( is_active_sidebar( 'sidebar-4' ) ) : ?>
		<div id="secondary1" class="widget-area right" role="complementary">
			<?php dynamic_sidebar( 'sidebar-4' ); ?>
		</div><!-- #secondary -->
	<?php endif; ?>
	<?php if ( is_active_sidebar( 'sidebar-1-top' ) ) : ?>
		<div id="secondary2" class="widget-area right" role="complementary">
			<?php dynamic_sidebar( 'sidebar-1-top' ); ?>
		</div><!-- #secondary -->
	<?php endif; ?>
	<?php if ( is_active_sidebar( 'sidebar-1' ) ) : ?>
		<div id="secondary" class="widget-area right topSsidebar" role="complementary">
			<span class="titelWcon"><h3 class="widget-title">סנן תוצאות לפי ...</h3></span>
			<?php dynamic_sidebar( 'sidebar-1' ); ?>
		</div><!-- #secondary -->
	<?php endif; ?>
	