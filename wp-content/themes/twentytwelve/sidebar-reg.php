<?php

if ( ! is_active_sidebar( 'sidebar-banner-reg' ))
	return;

// If we get this far, we have widgets. Let do this.
?>
	<?php if ( is_active_sidebar( 'sidebar-banner-reg' ) ) : ?>
	<div class="regularpage-sidebar">
		<?php dynamic_sidebar( 'sidebar-banner-reg' ); ?>
	</div><!-- .second -->
	
	<?php endif; ?>