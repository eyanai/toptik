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
 if ( ! is_active_sidebar( 'sidebar-atgs' ))
	return;

?>
<?php if ( is_active_sidebar( 'sidebar-atgs' ) ) : ?>
		<div id="secondary" class="widget-area right topSsidebar tags" role="complementary">
			<span class="titelWcon"><h3 class="widget-title">סנן תוצאות לפי ...</h3></span>
			<?php dynamic_sidebar('sidebar-atgs'); ?>
		</div><!-- #secondary -->
<?php endif; ?>